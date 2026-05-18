<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Tactile Whisper - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css'])
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div>
                <div class="admin-profile">
                    <img src="/images/admin-profile.jpg" alt="Admin" class="profile-img" style="border-radius: 50%; width: 50px; height: 50px; object-fit: cover;">
                    <div class="text-group"><h3>Admin Plush</h3></div>
                </div>
                <nav class="sidebar-nav">
                    <ul>
                        <li class="{{ Request::routeIs('admin.dashboard') ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-clock"></i> Pending Orders</a></li>
                        <li class="{{ Request::routeIs('admin.approved') ? 'active' : '' }}"><a href="{{ route('admin.approved') }}"><i class="fa-solid fa-circle-check"></i> Approved Orders</a></li>
                        <li class="{{ Request::routeIs('admin.all') ? 'active' : '' }}"><a href="{{ route('admin.all') }}"><i class="fa-solid fa-list"></i> All Transactions</a></li>
                        <li class="{{ Request::routeIs('admin.users*') ? 'active' : '' }}"><a href="{{ route('admin.users') }}"><i class="fa-solid fa-users"></i> Customers</a></li>
                        <li class="{{ Request::routeIs('admin.products*') ? 'active' : '' }}">
                            <a href="{{ route('admin.products') }}"><i class="fa-solid fa-box-open"></i> Products</a>
                        </li>
                    </ul>
                </nav>
                <div class="logout-container" style="padding: 20px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="approve-btn btn-red" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px; font-size: 14px; font-weight: bold; border-radius: 8px;">
                            <i class="fa-solid fa-right-from-bracket"></i> Log out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <header class="content-header">
                <div class="welcome-message">
                    <h2>@yield('pageTitle')</h2>
                    <p>@yield('pageSubtitle')</p>
                </div>
            </header>

<!-- ALERT NOTIF -->
            @if(session('success')) 
                <div style="background-color: #d4edda; color: #191557; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #28a745;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div> 
            @endif

            @if(session('error')) 
                <div style="background-color: #fce4e6; color: #8c2a38; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #8c2a38;">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
                </div> 
            @endif

            <section class="recent-orders">
                @yield('content')
            </section>
        </main>
    </div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        
        // A. Animasi untuk Header Seksi dan Pembungkus Form/Tabel
        const mainElements = document.querySelectorAll('.section-header, .form-container, .data-table, form[action*="admin.all"]');
        mainElements.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.5s ease, transform 0.5s cubic-bezier(0.25, 0.8, 0.25, 1)';
            
            setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, 100 + (index * 150));
        });

        // B. Animasi Staggered (Berurutan) untuk Baris Tabel
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateX(-15px)';
            row.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            
            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
            }, 300 + (index * 60));
        });

        // C. Animasi Staggered untuk Kolom Input di dalam Form
        const formGroups = document.querySelectorAll('.form-group');
        formGroups.forEach((group, index) => {
            group.style.opacity = '0';
            group.style.transform = 'translateY(10px)';
            group.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            
            setTimeout(() => {
                group.style.opacity = '1';
                group.style.transform = 'translateY(0)';
            }, 250 + (index * 80));
        });

        // D. Animasi Sidebar & Menu Item Berurutan
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.style.opacity = '0';
            sidebar.style.transform = 'translateX(-30px)';
            sidebar.style.transition = 'opacity 0.6s ease, transform 0.6s cubic-bezier(0.25, 0.8, 0.25, 1)';
            
            setTimeout(() => {
                sidebar.style.opacity = '1';
                sidebar.style.transform = 'translateX(0)';
            }, 50);

            const sidebarItems = document.querySelectorAll('.sidebar-nav li');
            sidebarItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-15px)';
                item.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, 200 + (index * 80));
            });
        }

    });
</script>

@yield('scripts')

</body>
</html>
