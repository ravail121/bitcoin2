<?php

namespace App\Http\Controllers;

use App\Models\GeneralSettings;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\Slider;
use App\Models\Social;
use App\Models\Service;
use App\Models\PaymentMethodsCategories;
use App\Http\Requests\WebSetting\UpdateLogoFormRequest;
use App\Http\Requests\WebSetting\UpdateContactFormRequest;
use App\Http\Requests\WebSetting\StoreSocialFormRequest;
use App\Http\Requests\WebSetting\StoreMenuFormRequest;
use App\Http\Requests\WebSetting\UpdateCopyrightFormRequest;
use App\Models\PaymentMethodAdvise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use File;
// use Image;
use Storage;
use Intervention\Image\Facades\Image;

class WebSettingController extends Controller
{

    public function manageLogo()
    {
        $data['page_title'] = "Manage Logo & Favicon";
        return view('webcontrol.logo', $data);
    }

    public function updateLogo(UpdateLogoFormRequest $request)
    {  
        //  return $request->banner;
        $basic = GeneralSettings::first();
        $in = $request->except('_method', '_token');
        
        $in = $request->except('_method', '_token');
        if ($request->hasFile('logo')) {
            $imgPath = $request->file('logo')->storeAs(
                'logo', 'logo.png'
            );
            
        }

        if ($request->hasFile('favicon')) {
            $imgPath = $request->file('favicon')->storeAs(
                'logo', 'favicon.png'
            );
        }
        if ($request->hasFile('banner')) {
            // $imgPath = $request->file('banner')->storeAs(
            //     'logo', 'banner.png'
            // );
            $image = $request->file('banner');
            $img = Image::make($image->getRealPath());
            $img->resize(5760 , 2844, function ($constraint) {
                $constraint->aspectRatio();                 
            });

            $img->stream(); // <-- Key point

            
            Storage::put('logo/banner.jpg', $img, 'public');
            
        }
        unset($in['logo']);
        unset($in['favicon']);
        unset($in['banner']);
        $basic->fill($in)->save();
        $notification = [
            'message' => 'Update Successfully',
            'alert-type' => 'success',
        ];

        return back()->with($notification);
    }

    public function getContact()
    {
        $data['basic'] = GeneralSettings::first();
        $data['page_title'] = "Contact Settings";
        return view('webcontrol.contact-setting', $data);
    }

    public function putContactSetting(UpdateContactFormRequest $request)
    {
        $basic = GeneralSettings::first();
        $in = $request->except('_method', '_token');
        $basic->fill($in)->save();

        $notification =  array('message' => 'Contact  Updated Successfully', 'alert-type' => 'success');
        return back()->with($notification);
    }

    public function manageFooter()
    {
        $data['page_title'] = "Manage Web Footer";
        return view('webcontrol.footer', $data);
    }
    public function updateFooter(UpdateCopyrightFormRequest $request)
    {
        $basic = GeneralSettings::first();
        $in = $request->except('_method', '_token');
        $basic->fill($in)->save();
        $notification = array('message' => 'Web Footer Updated Successfully.', 'alert-type' => 'success');
        return back()->with($notification);
    }

    public function manageSocial()
    {
        $data['page_title'] = "Manage Social";
        $data['social'] = Social::all();
        return view('webcontrol.social', $data);
    }
    public function storeSocial(StoreSocialFormRequest $request)
    {
        $product = Social::create($request->input());
        return response()->json($product);
    }
    public function editSocial($product_id)
    {
        $product = Social::find($product_id);
        return response()->json($product);
    }
    public function updateSocial(StoreSocialFormRequest $request, $product_id)
    {
        $product = Social::find($product_id);
        $product->name = $request->name;
        $product->code = $request->code;
        $product->link = $request->link;
        $product->save();
        return response()->json($product);
    }
    public function deleteSocial($product_id)
    {
        $product = Social::destroy($product_id);
        return response()->json($product);
    }

    public function manageMenu()
    {
        $data['page_title'] = "Control Menu";
        $data['menus'] = Menu::paginate(2);
        return view('webcontrol.menu-show', $data);
    }
    public function createMenu()
    {
        $data['page_title'] = "Create Menu";
        return view('webcontrol.menu-create', $data);
    }
    public function storeMenu(StoreMenuFormRequest $request)
    {
        $in = $request->except('_method', '_token');
        $in['slug'] = str_slug($request->name);
        Menu::create($in);
        $notification = array('message' => 'Menu Created Successfully.', 'alert-type' => 'success');
        return back()->with($notification);
    }
    public function editMenu($id)
    {
        $data['page_title'] = "Edit Menu";
        $data['menu'] = Menu::findOrFail($id);
        return view('webcontrol.menu-edit', $data);
    }
    public function updateMenu(StoreMenuFormRequest $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $in = $request->except('_method', '_token');
        $in['slug'] = str_slug($request->name);
        $menu->fill($in)->save();
        $notification = array('message' => 'Menu Updated Successfully.', 'alert-type' => 'success');
        return back()->with($notification);
    }
    public function deleteMenu(Request $request, Menu $menu)
    {
        $menu->delete();
        $notification = array('message' => 'Menu Deleted Successfully.', 'alert-type' => 'success');
        return back()->with($notification);
    }


    public function manageSlider()
    {
        $data['page_title'] = "Manage Slider";
        $data['sliders'] = Slider::get();
        return view('webcontrol.slider', $data);
    }
    public function storeSlider(Request $request)
    {
        // $this->validate($request, [
        //     'image' => 'mimes:png,jpeg,jpg,gif'
        // ]);

        // $s =  Slider::find(5);
        // echo ini_get('upload_max_filesize')."<br>";
        // echo ini_get('post_max_size');exit;
        $in = $request->except('_method', '_token');
        if (isset($_FILES["image"])) {
            // @unlink(public_path() . '/assets/images/slider/' . $s->image);
            // $image = $request->file('image');
            $filename = 'slider_'.time().$_FILES['image']['name']; // 'slider_'.time().'.'.$image->extension();
            $location = public_path() . '/assets/images/slider/' . $filename;
            // Image::make($image)->resize(1920, 1080)->save($location);
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $location)){
                $in['image'] = $filename;
                Slider::create($in);
                $notification = array('message' => 'Slider Added Successfully.', 'alert-type' => 'success');
            }
            else{
                $notification = array('message' => 'Slider Upload Failed', 'alert-type' => 'alert');
            }
    
        }
        else{
            $notification = array('message' => 'Video File is missing.', 'alert-type' => 'alert');
        }
        return back()->with($notification);
    }
    public function deleteSlider(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        $slider = Slider::findOrFail($request->id);
        File::delete(public_path() . '/assets/images/slider/'.$slider->image);
        $slider->delete();

        $notification = array('message' => 'Slider Deleted Successfully.', 'alert-type' => 'success');
        return back()->with($notification);
    }


    public function categories(Request $request){
        $page_title = 'Payment Method Categories';
       $categories= PaymentMethodsCategories::all();
        return view('admin.payment-methods.categories', compact('categories', 'page_title'));

    }
    public function createCategories(Request $request)
    {
        $page_title = 'Create  Category';
        
        return view('admin.payment-methods.edit', compact('page_title'));
    }
    public function saveCategories(Request $request){
        $categories = PaymentMethodsCategories::create($request->all());

        

        return back()->with('success', 'Create Successfully!');
    }
    public function EditCategories($id){
        $page_title = 'Edit  Category';
        $category = PaymentMethodsCategories::find($id);

        

        return view('admin.payment-methods.edit', compact('page_title','category'));
    }
    public function updateCategories(Request $request,$id){
        $category = PaymentMethodsCategories::find($id);
        $category->update($request->all());
        return back()->with('success', 'Updated Successfully!');

    }
    public function DeleteCategories($id){
        $category = PaymentMethodsCategories::find($id);
        $category->delete();
        return back()->with('success', 'Deleted Successfully!');
    }
    public function advises(){
        $page_title = 'Payment Method Advices';
        $advises= PaymentMethodAdvise::all();
        return view('admin.payment-methods.advises', compact('advises', 'page_title'));

    }
    public function EditAdvises($id){
        $page_title = 'Edit Advice';
        $category = PaymentMethodAdvise::find($id);
        return view('admin.payment-methods.advisesedit', compact('page_title','category'));
    }
    public function updateAdvises(Request $request,$id){
        $category = PaymentMethodAdvise::find($id);
        $category->update($request->all());
        return back()->with('success', 'Updated Successfully!');

    }
    public function DeleteAdvises($id){
        $category = PaymentMethodAdvise::find($id);
        $category->delete();
        return back()->with('success', 'Deleted Successfully!');
    }
}
