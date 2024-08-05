<template>
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-container content">
                <div class="navbar-collapse" id="navbar-mobile">
                    <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                        <ul class="nav navbar-nav">
                            <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="fas fa-bars" style="color:#5526c5 !important"></i></a></li>
                        </ul>
                        <ul class="nav navbar-nav bookmark-icons text-gray">
                            <li class="nav-item d-none d-lg-block"><a class="nav-link text-gray" href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="لیست انجام کار"><i class="fas fa-check-square text-gray"></i></a></li>
                            <li class="nav-item d-none d-lg-block"><a class="nav-link text-gray" href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="گفتگو"><i class="fas fa-comment-alt text-gray"></i></a></li>
                            <li class="nav-item d-none d-lg-block"><a class="nav-link text-gray" href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="ایمیل"><i class="fas fa-envelope text-gray"></i></a></li>
                            <li class="nav-item d-none d-lg-block"><a class="nav-link text-gray" href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="تقویم"><i class="fas fa-calendar-week text-gray"></i></a></li>
                        </ul>
                    </div>
                    <ul class="nav navbar-nav float-right">
                        <li class="dropdown dropdown-language nav-item"><a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="flag-icon flag-icon-ir"></i><span class="selected-language">فارسی</span></a>
                            <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                                <a class="dropdown-item" href="#" data-language="fa">
                                    <i class="flag-icon flag-icon-ir"></i>فارسی
                                </a>
                            </div>
                        </li>
                        <li class="nav-item d-none d-lg-block" ><a class="nav-link nav-link-expand">
                            <i class="fas fa-expand" style="color:#626262 !important"></i>
                        </a></li>


                        <li class="dropdown dropdown-user nav-item">
                            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <div class="user-nav d-sm-flex d-none">
                                    <span class="user-name text-bold-600">
                                        {{$user.username}}
                                    </span>
                                    <span class="user-status">دردسترس</span>
                                </div>
                                <span>
                                    <img class="round" :src="$user.image" alt="avatar" height="40" width="40" id="headerProfileImage">
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <router-link class="dropdown-item" to="/profile">
                                    <i class="fas fa-user-alt"></i>
                                    پروفایل
                                </router-link>
                                <div class="dropdown-divider"></div>
                                <a href="/logout" class="dropdown-item"><i class="fas fa-sign-out-alt"></i>خروج</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</template>
