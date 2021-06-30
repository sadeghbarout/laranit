
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            <img src="/images/profile.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
            <a href="#" class="d-block">{{auth()->user()[COL_ADMIN_USERNAME]}}</a>
            </div>
        </div>


        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item"><router-link class="nav-link" to="/"><i class="fas fa-tachometer-alt"></i> <p>داشبورد</p></router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/project"><i class="fa fa-tasks"></i> <p>پروژه ها</p></router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/request"><i class="fa fa-paper-plane"></i> <p>درخواست ها</p></router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/reminder"><i class="fas fa-clock"></i> <p>یادآوری ها</p></router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/bugReport"><i class="fas fa-times-circle"></i> <p>خطاها ها</p></router-link></li>

                <li class="nav-item"><a class="nav-link" href="{{route('admin.logout')}}"><i class="fas fa-power-off"></i><p>خروج</p></a></li>


            </ul>
        </nav>




    </div>
</aside>
