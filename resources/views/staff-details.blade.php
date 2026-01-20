<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | {{ $staff->fullname }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/media/logo/getfund-logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite('resources/css/app.css')
</head>
<body class="antialiased" data-theme="light">
    <div class="staff-profile-container">
        <div class="staff-container">
            <!-- Header -->
            <div class="staff-header">
                <div class="staff-header-title">
                    <div class="staff-logo">
                        <img src="{{ asset('assets/media/logo/getfund-logo.png') }}" alt="GetFund Logo">
                    </div>
                    <div>
                        <div class="staff-header-main">Staff Verification</div>
                        <div class="staff-org-name">Ghana Education Trust Fund</div>
                    </div>
                </div>
                <div class="staff-theme-toggle" onclick="toggleTheme()">
                    <span class="theme-icon">☀️</span>
                </div>
            </div>

            <!-- Verification Banner -->
            <div class="staff-verification-banner">
                <div class="staff-verification-title">Official Staff Profile</div>
                <div class="staff-verification-subtitle">
                    This profile has been verified by GetFund Administration
                </div>
            </div>

            <!-- Profile Grid -->
            <div class="staff-profile-grid">
                <!-- Profile Card (Left) -->
                <div class="staff-card staff-profile-card">
                    <div class="staff-profile-image-wrapper">
                        <img src="{{ $staff->photo ? Storage::url($staff->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($staff->fullname) . '&size=200&background=e5e7eb&color=9ca3af' }}"
                             alt="{{ $staff->fullname }}"
                             class="staff-profile-image">
                        <div class="staff-status-badge {{ strtolower($staff->status ?? 'active') }}">
                            <span>✓</span>
                            <span>{{ ucfirst($staff->status ?? 'Active') }}</span>
                        </div>
                    </div>

                    <h1 class="staff-name">{{ $staff->fullname }}</h1>
                    <p class="staff-role">{{ $staff->position }}</p>
                    <div class="staff-id-badge">STAFF ID: {{ $staff->staff_id }}</div>

                    <div class="staff-valid-until">
                        <svg class="staff-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>
                            <strong>Verification Successful</strong>
                        </span>
                    </div>

                    <div class="staff-decorative-border"></div>
                </div>

                <!-- Information Card (Right) -->
                <div class="staff-card">
                    <!-- Personal Information Section -->
                    <div class="staff-info-section">
                        <h2 class="staff-info-title">
                            <svg class="staff-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Personal Information
                        </h2>
                        <div class="staff-info-grid">
                            <div class="staff-info-item">
                                <span class="staff-info-label">Full Name</span>
                                <span class="staff-info-value">{{ $staff->fullname }}</span>
                            </div>
                            <div class="staff-info-item">
                                <span class="staff-info-label">Employee ID</span>
                                <span class="staff-info-value">{{ $staff->staff_id }}</span>
                            </div>
                            <div class="staff-info-item">
                                <span class="staff-info-label">Department</span>
                                <span class="staff-info-value">{{ $staff->department->name ?? 'N/A' }}</span>
                            </div>
                            <div class="staff-info-item">
                                <span class="staff-info-label">Position</span>
                                <span class="staff-info-value">{{ $staff->position ?? 'N/A' }}</span>
                            </div>
                            <div class="staff-info-item">
                                <span class="staff-info-label">Employment Type</span>
                                <span class="staff-info-value">{{ $staff->employment_type ?? 'Full-time' }}</span>
                            </div>
                            <div class="staff-info-item">
                                <span class="staff-info-label">Date Joined</span>
                                <span class="staff-info-value">{{ $staff->date_joined ? $staff->date_joined->format('F j, Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="staff-info-section">
                        <h2 class="staff-info-title">
                            <svg class="staff-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Contact Information
                        </h2>
                        <div class="staff-contact-section">
                            @if($staff->email)
                            <a href="mailto:{{ $staff->email }}" class="staff-contact-item">
                                <svg class="staff-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $staff->email }}</span>
                            </a>
                            @endif
                            @if($staff->phone_number)
                            <a href="tel:{{ str_replace(' ', '', $staff->phone_number) }}" class="staff-contact-item">
                                <svg class="staff-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span>{{ $staff->phone_number }}</span>
                            </a>
                            @endif
                            @if($staff->location)
                            <div class="staff-contact-item">
                                <svg class="staff-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>{{ $staff->location }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }

        // Load saved theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        });
    </script>
</body>
</html>
