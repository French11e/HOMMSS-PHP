<ul class="account-nav">
    <li><a href="{{route('user.index')}}" class="menu-link menu-link_us-s {{ request()->routeIs('user.index') ? 'menu-link_active' : '' }}">Dashboard</a></li>
    <li><a href="{{route('user.orders')}}" class="menu-link menu-link_us-s {{ request()->routeIs('user.orders') ? 'menu-link_active' : '' }}">Orders</a></li>
    <li><a href="#" class="menu-link menu-link_us-s">Addresses</a></li>
    <li><a href="{{route('user.account.details')}}" class="menu-link menu-link_us-s {{ request()->routeIs('user.account.details') ? 'menu-link_active' : '' }}">Account Details</a></li>
    <li>
        <form method="POST" action="{{route('logout')}}" id="logout-form">
            @csrf
            <a href="{{route('logout')}}" class="menu-link menu-link_us-s" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
        </form>
    </li>
</ul>