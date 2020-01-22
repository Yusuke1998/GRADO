<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Image;

class PicPixReaderController extends Controller
{
    public function index()
    {
    	$images = Image::all();
        return view('principal',compact('images'));
    }

    public function load(Request $request)
    {
    	if ($request->hasFile('img')) {
            $file = $request->file('img');
            $nameImg = $file->getClientOriginalName();
            $file->move("imgOrigi/",$nameImg);

	    	Image::create([
	    		'name'	=>	$nameImg
	    	]);
        }
        return redirect(url('/'));
    }

    public function show($id)
    {
    	$img = Image::findOrFail($id);
    	return view('ver',compact('img'));
    }

    public function modify($id,$make)
    {
    	$img = Image::findOrFail($id);
    	$nameImg = $img->name;

    	if (file_exists('imgModif/'.$nameImg)) {
    		$file = 'imgModif/'.$nameImg;
    	}else{
    		$file = 'imgOrigi/'.$nameImg;
    	}

    	$px = new \PixReader;
    	$px->setImage($file);

    	switch ($make) {
    		case 'reset':
    			if(copy('imgOrigi/'.$nameImg, 'imgModif/'.$nameImg)){
	        		return redirect(url('/show',$id));
    			};
    			break;
    		case 'delete':
    			if(unlink('imgModif/'.$nameImg)){
	        		return redirect(url('/show',$id));
    			};
    			break;
    		case 'clustery':
    			$px->Clustering();
    			break;
            case 'spaceline':
                $px->lineSpace();
                break;
    		case 'squelet':
    			$px->squelettisation();
    			break;
    		case 'grayscale':
    			$px->image2gray();
    			break;
    		case 'backgroundBlack':
    			$px->paintPixel(white,black,black);
    			break;
    		case 'backgroundWhite':
    			$px->paintPixel(black,white,white);
    			break;
    		default:
    			break;
    	}

	    if ($px->saveImage($nameImg,'imgModif')) {
	        return redirect(url('/show',$id));
	    }
	    return redirect(url('/'));
    }

    public function delete($id)
    {
    	$img = Image::findOrFail($id);
    	if(file_exists('imgModif/'.$img->name)){unlink('imgModif/'.$img->name);}
    	if(file_exists('imgOrigi/'.$img->name)){unlink('imgOrigi/'.$img->name);}
    	$img->delete();
	    return redirect(url('/'));
    }
}
