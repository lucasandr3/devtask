@props(['name', 'class' => 'size-4'])

@php
$paths = match($name) {
    'dashboard' => '
        <rect width="7" height="9" x="3" y="3" rx="1"/>
        <rect width="7" height="5" x="14" y="3" rx="1"/>
        <rect width="7" height="9" x="14" y="12" rx="1"/>
        <rect width="7" height="5" x="3" y="16" rx="1"/>
    ',
    'projects' => '
        <path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/>
        <path d="M8 10v4"/><path d="M12 10v2"/><path d="M16 10v6"/>
    ',
    'tasks' => '
        <rect x="3" y="5" width="6" height="6" rx="1"/>
        <path d="m3 17 2 2 4-4"/>
        <path d="M13 6h8"/><path d="M13 12h8"/><path d="M13 18h8"/>
    ',
    'clock-in' => '
        <circle cx="12" cy="13" r="8"/>
        <path d="M12 9v4l2 2"/>
        <path d="M5 3 2 6"/><path d="m22 6-3-3"/>
        <path d="M6.38 18.7 4 21"/><path d="M17.64 18.67 20 21"/>
    ',
    'timesheet' => '
        <path d="M8 2v4"/><path d="M16 2v4"/>
        <rect width="18" height="18" x="3" y="4" rx="2"/>
        <path d="M3 10h18"/>
        <path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/>
        <path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/>
    ',
    'report' => '
        <path d="M15 12h-5"/><path d="M15 8h-5"/>
        <path d="M19 17V5a2 2 0 0 0-2-2H4"/>
        <path d="M8 21h12a2 2 0 0 0 2-2v-1a1 1 0 0 0-1-1H11a1 1 0 0 0-1 1v1a2 2 0 0 1-2 2z"/>
        <path d="M8 21V5a2 2 0 0 1 2-2h9"/>
    ',
    'company-hours' => '
        <path d="M10 12h4"/><path d="M10 8h4"/><path d="M14 21v-3a2 2 0 0 0-4 0v3"/>
        <path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2"/>
        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"/>
    ',
    'approve' => '
        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
        <path d="m9 14 2 2 4-4"/>
    ',
    'team' => '
        <path d="M18 21a8 8 0 0 0-16 0"/>
        <circle cx="10" cy="8" r="5"/>
        <path d="M22 20a8 8 0 0 0-2.357-5.857"/>
        <path d="M16 3.128a4 4 0 0 1 0 7.744"/>
    ',
    'logout' => '
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" x2="9" y1="12" y2="12"/>
    ',
    'user' => '
        <circle cx="12" cy="8" r="5"/>
        <path d="M20 21a8 8 0 0 0-16 0"/>
    ',
    'settings' => '
        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
        <circle cx="12" cy="12" r="3"/>
    ',
    'search' => '
        <circle cx="11" cy="11" r="8"/>
        <path d="m21 21-4.3-4.3"/>
    ',
    'plus' => '
        <path d="M5 12h14"/><path d="M12 5v14"/>
    ',
    'chevron-down' => '
        <path d="m6 9 6 6 6-6"/>
    ',
    'table' => '
        <path d="M12 3v18"/>
        <rect width="18" height="18" x="3" y="3" rx="2"/>
        <path d="M3 9h18"/><path d="M3 15h18"/>
    ',
    'kanban' => '
        <path d="M6 5v11"/><path d="M12 5v6"/><path d="M18 5v14"/>
    ',
    'filter' => '
        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
    ',
    'sliders' => '
        <line x1="21" x2="14" y1="4" y2="4"/>
        <line x1="10" x2="3" y1="4" y2="4"/>
        <line x1="21" x2="12" y1="12" y2="12"/>
        <line x1="8" x2="3" y1="12" y2="12"/>
        <line x1="21" x2="16" y1="20" y2="20"/>
        <line x1="12" x2="3" y1="20" y2="20"/>
        <line x1="14" x2="14" y1="2" y2="6"/>
        <line x1="8" x2="8" y1="10" y2="14"/>
        <line x1="16" x2="16" y1="18" y2="22"/>
    ',
    default => '',
};
@endphp

<svg
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="2"
    stroke-linecap="round"
    stroke-linejoin="round"
    {{ $attributes->merge(['class' => $class]) }}
    aria-hidden="true"
>{!! $paths !!}</svg>
