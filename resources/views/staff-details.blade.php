<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} | {{ $staff->fullname }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --bg-card: #ffffff;
            --text-primary: #1a1a1a;
            --text-secondary: #6c757d;
            --border-color: #e0e0e0;
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            --accent-green: #10b981;
            --accent-red: #ef4444;
            --accent-blue: #3b82f6;
            --getfund-primary: #059669;
            --getfund-gold: #10b981;
        }

        [data-theme="dark"] {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-color: #334155;
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            width: 60px;
            height: 60px;
            background: var(--bg-card);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow);
            border: 2px solid var(--border-color);
        }

        .logo-text {
            font-weight: bold;
            color: var(--getfund-primary);
            font-size: 1.2rem;
        }

        .org-name {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
        }

        .theme-toggle {
            width: 50px;
            height: 26px;
            background: var(--bg-secondary);
            border-radius: 13px;
            position: relative;
            cursor: pointer;
            border: 2px solid var(--border-color);
            transition: background-color 0.3s ease;
        }

        .theme-toggle::before {
            content: '☀️';
            position: absolute;
            left: 3px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            transition: left 0.3s ease;
        }

        [data-theme="dark"] .theme-toggle::before {
            content: '🌙';
            left: 25px;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 968px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .profile-card {
            text-align: center;
        }

        .profile-image-wrapper {
            position: relative;
            width: 200px;
            height: 200px;
            margin: 0 auto 1.5rem;
        }

        .profile-image {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--border-color);
            box-shadow: var(--shadow);
        }

        .status-badge {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: var(--accent-green);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .status-badge.inactive {
            background: var(--accent-red);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .staff-name {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .staff-role {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .staff-id-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--getfund-primary), #10b981);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.125rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3);
        }

        .valid-until {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-top: 1rem;
            padding: 0.75rem;
            background: var(--bg-secondary);
            border-radius: 8px;
        }

        .info-section {
            margin-bottom: 2rem;
        }

        .info-section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .info-value {
            font-size: 1rem;
            color: var(--text-primary);
            font-weight: 600;
        }

        .contact-section {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: var(--bg-secondary);
            border-radius: 8px;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .contact-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
            border-color: var(--accent-blue);
        }

        .verification-banner {
            background: linear-gradient(135deg, var(--getfund-primary), #10b981);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(5, 150, 105, 0.2);
        }

        .verification-banner-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .verification-banner-subtitle {
            font-size: 0.95rem;
            opacity: 0.95;
        }

        .decorative-border {
            height: 8px;
            background: linear-gradient(90deg,
                var(--getfund-primary) 0%,
                var(--getfund-gold) 25%,
                var(--accent-green) 50%,
                var(--accent-blue) 75%,
                var(--getfund-primary) 100%);
            border-radius: 4px;
            margin-top: 1rem;
        }

        .badge-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .badge {
            background: var(--bg-secondary);
            color: var(--text-primary);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            border: 1px solid var(--border-color);
        }

        .verification-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .verification-item {
            background: var(--bg-secondary);
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid var(--accent-green);
        }

        .verification-item-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
        }

        .verification-item-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .staff-name {
                font-size: 1.5rem;
            }

            .profile-image-wrapper {
                width: 160px;
                height: 160px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        .icon {
            width: 20px;
            height: 20px;
            display: inline-block;
        }

        .footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .footer a {
            color: var(--getfund-primary);
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <img src="{{ asset('assets/media/logo/getfund-logo.png') }}" alt="GetFund Logo" style="height: 60px; width: auto;">
                <div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">Staff Verification</div>
                    <div class="org-name">Ghana Education Trust Fund</div>
                </div>
            </div>
            <div class="theme-toggle" onclick="toggleTheme()"></div>
        </div>

        <div class="verification-banner">
            <div class="verification-banner-title">Official Staff Profile</div>
            <div class="verification-banner-subtitle">This profile has been verified by GetFund Administration</div>
        </div>

        <div class="profile-grid">
            <div class="card profile-card">
                <div class="profile-image-wrapper">
                    <img src="{{ Storage::url($staff->photo) }}" alt="{{ $staff->fullname }}" class="profile-image">
                    @if($staff->status === 'active' || $staff->status === 'Active')
                        <div class="status-badge">
                            <span>✓</span>
                            <span>Active</span>
                        </div>
                    @else
                        <div class="status-badge inactive">
                            <span>✗</span>
                            <span>Inactive</span>
                        </div>
                    @endif
                </div>

                <h1 class="staff-name">{{ $staff->fullname }}</h1>
                <p class="staff-role">{{ $staff->position }}</p>
                <div class="staff-id-badge">STAFF ID: {{ $staff->staff_id }}</div>

                <div class="valid-until">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Valid until: <strong>{{ $staff->valid_until ? \Carbon\Carbon::parse($staff->valid_until)->format('F d, Y') : 'N/A' }}</strong></span>
                </div>

                <div class="decorative-border"></div>
            </div>

            <div class="card">
                <div class="info-section">
                    <h2 class="info-section-title">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Personal Information
                    </h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">{{ $staff->fullname }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Employee ID</span>
                            <span class="info-value">{{ $staff->staff_id }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Department</span>
                            <span class="info-value">{{ $staff->department->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Position</span>
                            <span class="info-value">{{ $staff->position }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Employment Type</span>
                            <span class="info-value">{{ $staff->employment_type ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Date Joined</span>
                            <span class="info-value">{{ $staff->date_joined ? \Carbon\Carbon::parse($staff->date_joined)->format('F d, Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="info-section" style="margin-bottom: 0;">
                    <h2 class="info-section-title">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contact Information
                    </h2>
                    <div class="contact-section">
                        <a href="mailto:{{ $staff->email }}" class="contact-item">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ $staff->email }}</span>
                        </a>
                        <a href="tel:{{ $staff->phone_number }}" class="contact-item">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>{{ $staff->phone_number ?? 'N/A' }}</span>
                        </a>
                        <div class="contact-item">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $staff->location ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Copyright &copy; {{ now()->format('Y') }} Getfund | Powered by: <a href="javascript:void(0)">rCodez</a></p>
        </div>
    </div>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }

        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
</body>
</html>
