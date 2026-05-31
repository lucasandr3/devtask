@props(['name', 'class' => 'size-4'])

@php
    $materialIcon = match ($name) {
        'dashboard' => 'space_dashboard',
        'projects' => 'folder_open',
        'tasks' => 'task_alt',
        'clock-in' => 'more_time',
        'timesheet' => 'calendar_month',
        'report' => 'description',
        'company-hours' => 'corporate_fare',
        'approve' => 'fact_check',
        'team' => 'groups',
        'logout' => 'logout',
        'user' => 'person',
        'settings' => 'settings',
        'search' => 'search',
        'plus' => 'add',
        'chevron-down' => 'expand_more',
        'chevron-right' => 'chevron_right',
        'arrow-back' => 'arrow_back',
        'table' => 'table_rows',
        'kanban' => 'view_kanban',
        'filter' => 'filter_list',
        'sliders' => 'tune',
        'finance' => 'account_balance_wallet',
        'clients' => 'groups',
        'invoice' => 'receipt_long',
        'edit' => 'edit',
        'delete' => 'delete',
        'view', 'visibility' => 'visibility',
        'download' => 'download',
        'upload' => 'upload_file',
        'mail', 'email' => 'mail',
        'warning' => 'warning',
        'notifications' => 'notifications',
        'menu' => 'menu',
        'close' => 'close',
        'check' => 'check_circle',
        'play' => 'play_arrow',
        'stop' => 'stop',
        default => 'help',
    };
@endphp

<span
    {{ $attributes->merge(['class' => 'material-symbols-outlined '.$class]) }}
    aria-hidden="true"
>{{ $materialIcon }}</span>
