<!-- For images that should be lazy loaded with placeholder -->
<img loading="lazy" 
     src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 330 400'%3E%3C/svg%3E"
     data-src="{{asset('uploads/products')}}/{{$product->image}}" 
     width="330" height="400" alt="{{$product->name}}" class="lazy">

<!-- For images that should work even without JavaScript -->
<img loading="lazy" 
     src="{{asset('uploads/products')}}/{{$product->image}}" 
     width="330" height="400" alt="{{$product->name}}">