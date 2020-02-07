<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Image;

class PicPixReaderController extends Controller
{
    public function index()
    {
        return view('inicio');
    }

    public function allImages()
    {
        $images = Image::orderBy('created_at','desc')->get();
        return $images;
    }

    public function findID($id)
    {
        $img = Image::findOrFail($id);
        return $img;
    }

    public function action($id,$make)
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
                    return response([
                        'status' => 'success'
                    ], 200);
                };
                break;
            case 'delete':
                if(unlink('imgModif/'.$nameImg)){
                    return response([
                        'status' => 'success'
                    ], 200);
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
                $px->paintPixel(16777215,000,000);
                break;
            case 'backgroundWhite':
                $px->paintPixel(000,16777215,16777215);
                break;
            default:
                break;
        }

        if ($px->saveImage($nameImg,'imgModif')) {
            return response([
                'status' => 'success'
            ], 200);
        }
    }

    public function save(Request $request)
    {
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $nameImg = $file->getClientOriginalName();
            $file->move("imgOrigi/",$nameImg);

            Image::create([
                'name'  =>  $nameImg
            ]);
            
            return response([
                'status' => 'success'
            ], 200);
        }
        return response([
            'status' => 'warning'
        ], 200);
    }

    public function destroy($id)
    {
        $img = Image::findOrFail($id);
        if(file_exists('imgModif/'.$img->name)){unlink('imgModif/'.$img->name);}
        if(file_exists('imgOrigi/'.$img->name)){unlink('imgOrigi/'.$img->name);}
        $img->delete();

        return response([
            'status' => 'success'
        ], 200);
    }





















    public function picpixreader()
    {
        $images = Image::all();
        return view('simple.principal',compact('images'));
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
    	return view('simple.ver',compact('img'));
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
