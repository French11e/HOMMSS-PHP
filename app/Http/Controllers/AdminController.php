<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {
        // Get the latest 10 orders
        $orders = Order::orderBy('created_at', 'DESC')->get()->take(10);

        // Get the dashboard data from the database
        $dashboardDatas = DB::select("SELECT 
                                    SUM(total) AS TotalAmount,
                                    SUM(IF(status = 'ordered', total, 0)) AS TotalOrderedAmount,
                                    SUM(IF(status = 'delivered', total, 0)) AS TotalDeliveredAmount,
                                    SUM(IF(status = 'canceled', total, 0)) AS TotalCanceledAmount,
                                    COUNT(*) AS Total,
                                    SUM(IF(status = 'ordered', 1, 0)) AS TotalOrdered,
                                    SUM(IF(status = 'delivered', 1, 0)) AS TotalDelivered,
                                    SUM(IF(status = 'canceled', 1, 0)) AS TotalCanceled
                                    FROM orders");

        // Get monthly data for the dashboard
        $monthlyDatas = DB::select("SELECT 
                                    M.id AS MonthNo, M.name AS MonthName,
                                    IFNULL(D.TotalAmount, 0) AS TotalAmount,
                                    IFNULL(D.TotalOrderedAmount, 0) AS TotalOrderedAmount,
                                    IFNULL(D.TotalDeliveredAmount, 0) AS TotalDeliveredAmount, 
                                    IFNULL(D.TotalCanceledAmount, 0) AS TotalCanceledAmount 
                                    FROM month_names M
                                    LEFT JOIN (
                                    SELECT 
                                    MONTH(created_at) AS MonthNo,
                                    SUM(total) AS TotalAmount,
                                    SUM(IF(status = 'ordered', total, 0)) AS TotalOrderedAmount,
                                    SUM(IF(status = 'delivered', total, 0)) AS TotalDeliveredAmount,
                                    SUM(IF(status = 'canceled', total, 0)) AS TotalCanceledAmount
                                    FROM orders 
                                    WHERE YEAR(created_at) = YEAR(NOW()) 
                                    GROUP BY MONTH(created_at)
                                ) D ON D.MonthNo = M.id 
                                ORDER BY M.id");
        $AmountM = implode(',', collect($monthlyDatas)->pluck('TotalAmount')->toArray());
        $OrderedAmountM = implode(',', collect($monthlyDatas)->pluck('TotalOrderedAmount')->toArray());
        $DeliveredAmountM = implode(',', collect($monthlyDatas)->pluck('TotalDeliveredAmount')->toArray());
        $CanceledAmountM = implode(',', collect($monthlyDatas)->pluck('TotalCanceledAmount')->toArray());
        $TotalAmount = collect($monthlyDatas)->sum('TotalAmount');
        $TotalOrderedAmount = collect($monthlyDatas)->sum('TotalOrderedAmount');
        $TotalDeliveredAmount = collect($monthlyDatas)->sum('TotalDeliveredAmount');
        $TotalCanceledAmount = collect($monthlyDatas)->sum('TotalCanceledAmount');
        return view('admin.index', compact('orders', 'dashboardDatas', 'AmountM', 'OrderedAmountM', 'DeliveredAmountM', 'CanceledAmountM', 'TotalAmount', 'TotalOrderedAmount', 'TotalDeliveredAmount', 'TotalCanceledAmount'));
    }



    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function add_brand()
    {
        return view('admin.brand-add');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateBrandThumbnailsImage($image, $file_name);
            $brand->image = $file_name;
        }

        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Brand has been added successfully');
    }

    public function brand_edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand-edit', compact('brand'));
    }

    public function brand_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048',
        ]);

        $brand = Brand::findOrFail($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            if ($brand->image && File::exists(public_path('uploads/brands/' . $brand->image))) {
                File::delete(public_path('uploads/brands/' . $brand->image));
            }

            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateBrandThumbnailsImage($image, $file_name);
            $brand->image = $file_name;
        }

        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Brand has been updated successfully');
    }

    public function GenerateBrandThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $img = Image::read($image->path());
        $img->cover(124, 124)->save($destinationPath . '/' . $imageName);
    }

    public function brand_delete($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return redirect()->route('admin.brands')->with('status', 'Brand not found');
        }

        if ($brand->image && File::exists(public_path('uploads/brands/' . $brand->image))) {
            File::delete(public_path('uploads/brands/' . $brand->image));
        }

        $brand->delete();
        return redirect()->route('admin.brands')->with('status', 'Brand has been deleted successfully');
    }

    public function categories()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function category_add()
    {
        return view('admin.category-add');
    }

    public function category_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateCategoryThumbnailsImage($image, $file_name);
            $category->image = $file_name;
        }

        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Category has been added successfully');
    }

    public function GenerateCategoryThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/categories');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $img = Image::read($image->path());
        $img->cover(124, 124)->save($destinationPath . '/' . $imageName);
    }

    public function category_edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category-edit', compact('category'));
    }

    public function category_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048',
        ]);

        $category = Category::findOrFail($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            if ($category->image && File::exists(public_path('uploads/categories/' . $category->image))) {
                File::delete(public_path('uploads/categories/' . $category->image));
            }

            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateCategoryThumbnailsImage($image, $file_name);
            $category->image = $file_name;
        }

        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Category has been updated successfully');
    }

    public function category_delete($id)
    {
        $category = Category::find($id);
        if (File::exists(public_path('uploads/categories') . '/' . $category->image)) {
            File::delete(public_path('uploads/categories') . '/' . $category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status', 'Category has been deleted successfully');
    }

    public function products()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function product_add()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories', 'brands'));
    }

    public function product_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required|boolean',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if ($request->hasFile('images')) {
            $allowedfileExtion = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            foreach ($files as $file) {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension, $allowedfileExtion);

                if ($gcheck) {
                    $gfileName = $current_timestamp . "-" . $counter . '.' . $gextension;
                    $this->GenerateProductThumbnailImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status', 'Product has been added successfully');
    }

    public function GenerateProductThumbnailImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/products'); // Full-size image folder
        $destinationPathThumbnail = public_path('uploads/products/thumbnails'); // Thumbnail folder

        // Save full-size image
        $img = Image::read($image->path());
        $img->cover(540, 689, "top");
        $img->resize(540, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName); // Save full-size image here

        // Save thumbnail in the thumbnail folder
        $img->resize(104, 104, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPathThumbnail . '/' . $imageName); // Save thumbnail here
    }


    public function product_edit($id)
    {
        $product = Product::find($id);
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

    public function product_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,' . $request->id,
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required|boolean',
            'quantity' => 'required|integer|min:1',
            'image' => 'mimes:png,jpg,jpeg|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required',
        ]);

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if ($request->hasFile('image')) {

            if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
                File::delete(public_path('uploads/products') . '/' . $product->image);
            }

            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
            }
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if ($request->hasFile('images')) {

            foreach (explode(',', $product->images) as $ofile) {

                if (File::exists(public_path('uploads/products') . '/' . $ofile)) {
                    File::delete(public_path('uploads/products') . '/' . $ofile);
                }

                if (File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile)) {
                    File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
                }
            }

            $allowedfileExtion = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            foreach ($files as $file) {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension, $allowedfileExtion);

                if ($gcheck) {
                    $gfileName = $current_timestamp . "-" . $counter . '.' . $gextension;
                    $this->GenerateProductThumbnailImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status', 'Product has been updated successfully');
    }

    public function product_delete($id)
    {
        $product = Product::find($id);
        if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
            File::delete(public_path('uploads/products') . '/' . $product->image);
        }
        if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
            File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
        }

        foreach (explode(',', $product->images) as $ofile) {

            if (File::exists(public_path('uploads/products') . '/' . $ofile)) {
                File::delete(public_path('uploads/products') . '/' . $ofile);
            }

            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
            }
        }

        $product->delete();
        return redirect()->route('admin.products')->with('status', 'Product has been deleted successfully');
    }

    public function orders()
    {
        $orders = Order::orderBy('created_at', 'DESC')->paginate(12);
        return view('admin.orders', compact('orders'));
    }

    public function order_details($order_id)
    {
        $order = Order::with(['user', 'user.orders'])->find($order_id);
        if (!$order) {
            abort(404, 'Order not found');
        }

        $orderItems = OrderItem::where('order_id', $order_id)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('order_id', $order_id)->first();

        return view('admin.order-details', compact('order', 'orderItems', 'transaction'));
    }

    public function orderPackingSlip($order_id)
    {
        $order = Order::with(['user', 'orderItems.product'])->find($order_id);
        if (!$order) {
            abort(404, 'Order not found');
        }

        return view('admin.order-packing-slip', compact('order'));
    }

    public function update_order_status(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        // Store previous status to check if status changed
        $previousStatus = $order->status;

        // Make sure the status value is properly quoted as a string
        $order->status = (string)$request->order_status;

        // Set or clear appropriate dates based on status
        if ($request->order_status == 'ordered') {
            // Clear processing, shipped, and delivered dates when moved back to ordered
            $order->processing_date = null;
            $order->shipped_date = null;
            $order->delivered_date = null;
        } elseif ($request->order_status == 'processing') {
            // Set processing date, clear shipped and delivered dates
            $order->processing_date = Carbon::now();
            $order->shipped_date = null;
            $order->delivered_date = null;
        } elseif ($request->order_status == 'shipped') {
            // Set shipped date if not already set, clear delivered date
            if (!$order->shipped_date) {
                $order->shipped_date = Carbon::now();
            }
            $order->delivered_date = null;
        } elseif ($request->order_status == 'delivered') {
            // Set delivered date if not already set
            if (!$order->delivered_date) {
                $order->delivered_date = Carbon::now();
            }
        } elseif ($request->order_status == 'canceled') {
            // Set canceled date if not already set
            if (!$order->canceled_date) {
                $order->canceled_date = Carbon::now();
            }
        }

        $order->save();

        // Add a note about the status change
        $statusNote = "Order status changed from '{$previousStatus}' to '{$request->order_status}' on " . now()->format('Y-m-d H:i:s');
        $order->notes = ($order->notes ? $order->notes . "\n" : '') . $statusNote;
        $order->save();

        // Send email notification if status changed to processing
        if ($request->order_status == 'processing' && $previousStatus != 'processing') {
            $emailSent = $this->sendOrderProcessingEmail($order);
            if ($emailSent) {
                return back()->with('status', 'Order status updated and processing confirmation email sent to customer.');
            } else {
                return back()->with('status', 'Order status updated but failed to send processing confirmation email.');
            }
        }

        // Send email notification if status changed to shipped
        if ($request->order_status == 'shipped' && $previousStatus != 'shipped') {
            $emailSent = $this->sendOrderShippedEmail($order);
            if ($emailSent) {
                return back()->with('status', 'Order status updated and shipping confirmation email sent to customer.');
            } else {
                return back()->with('status', 'Order status updated but failed to send shipping confirmation email.');
            }
        }

        // Update transaction status if order is delivered
        if ($request->order_status == 'delivered') {
            $transaction = Transaction::where('order_id', $order->id)->first();
            if ($transaction) {
                $transaction->status = 'approved';
                $transaction->save();
            }
        }

        return back()->with('status', 'Order status has been updated successfully.');
    }

    private function sendOrderProcessingEmail($order)
    {
        // Load the user to get their email
        $user = User::find($order->user_id);

        if ($user && $user->email) {
            try {
                Mail::send('emails.order-processing', ['order' => $order], function ($message) use ($user, $order) {
                    $message->to($user->email)
                        ->subject('Your Order #' . $order->id . ' Is Being Processed');
                });

                // Log the successful email sending
                Log::info('Processing confirmation email sent to: ' . $user->email . ' for order #' . $order->id);

                // Add a note to the order
                $order->notes = ($order->notes ? $order->notes . "\n" : '') .
                    "Processing confirmation email sent to " . $user->email . " on " . now()->format('Y-m-d H:i:s');
                $order->save();

                return true;
            } catch (\Exception $e) {
                // Log the error
                Log::error('Failed to send processing confirmation email: ' . $e->getMessage());
                return false;
            }
        }

        return false;
    }

    private function sendOrderShippedEmail($order)
    {
        // Load the user to get their email
        $user = User::find($order->user_id);

        if ($user && $user->email) {
            try {
                Mail::send('emails.order-shipped', ['order' => $order], function ($message) use ($user, $order) {
                    $message->to($user->email)
                        ->subject('Your Order #' . $order->id . ' Has Been Shipped');
                });

                // Log the successful email sending
                Log::info('Shipping confirmation email sent to: ' . $user->email . ' for order #' . $order->id);

                // Add a note to the order
                $order->notes = ($order->notes ? $order->notes . "\n" : '') .
                    "Shipping confirmation email sent to " . $user->email . " on " . now()->format('Y-m-d H:i:s');
                $order->save();

                return true;
            } catch (\Exception $e) {
                // Log the error
                Log::error('Failed to send shipping confirmation email: ' . $e->getMessage());
                return false;
            }
        }

        return false;
    }
}
