 <div class="sidebar-area" id="sidebar-area">
        <div class="logo position-relative">
            <a href="index.html" class="d-block text-decoration-none">
                <img src="{{ asset('backend/assets/images/pngcs-logo.png') }}" width="80" alt="logo-icon">
                {{-- <span class="logo-text fw-bold text-dark">Farol</span> --}}
            </a>
            <button
                class="sidebar-burger-menu bg-transparent p-0 border-0 opacity-0 z-n1 position-absolute top-50 end-0 translate-middle-y"
                id="sidebar-burger-menu">
                <i data-feather="x"></i>
            </button>
        </div>
        <aside id="layout-menu" class="layout-menu menu-vertical menu active" data-simplebar>
            <ul class="menu-inner">
                <li class="menu-item">
                    <a href="{{ route('dasbor') }}" class="menu-link">
                        <i data-feather="grid" class="menu-icon tf-icons"></i>
                        <span class="title">Dasbor</span>
                    </a>
                </li>
                <li class="menu-title small text-uppercase">
                    <span class="menu-title-text">DATA</span>
                </li>
                    <li class="menu-item">
                        <a href="{{ route('invoice.index') }}" class="menu-link">
                            <i data-feather="user" class="menu-icon tf-icons"></i>
                            <span class="title">Data Invoice</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('packing-list.index') }}" class="menu-link">
                            <i data-feather="user" class="menu-icon tf-icons"></i>
                            <span class="title">Data Packing List</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle active">
                            <i data-feather="columns" class="menu-icon tf-icons"></i>
                            <span class="title">Kantor Pabean</span>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="" class="menu-link">
                                    Kategori Kantor Pabean
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="" class="menu-link">
                                    Kantor Pabean
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                
                
               
                
              
                
            </ul>
        </aside>
       
    </div>