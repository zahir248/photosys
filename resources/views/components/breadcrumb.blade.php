@props(['items' => []])

<nav aria-label="breadcrumb" class="breadcrumb-nav">
    <div class="container">
        <ol class="breadcrumb">
            @foreach($items as $index => $item)
                @if($index === 0)
                    <!-- Home icon -->
                    <li class="breadcrumb-item">
                        <a href="{{ $item['url'] ?? '#' }}" class="breadcrumb-link">
                            <i class="bi bi-house"></i>
                        </a>
                    </li>
                @else
                    <!-- Separator -->
                    <li class="breadcrumb-separator">
                        <i class="bi bi-chevron-right"></i>
                    </li>
                    
                    <!-- Breadcrumb item -->
                    <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                        @if($loop->last)
                            <span class="breadcrumb-current">{{ $item['label'] }}</span>
                        @else
                            <a href="{{ $item['url'] ?? '#' }}" class="breadcrumb-link">{{ $item['label'] }}</a>
                        @endif
                    </li>
                @endif
            @endforeach
        </ol>
    </div>
</nav>

<style>
.breadcrumb-nav {
    background: #ffffff;
    border-bottom: 1px solid #e9ecef;
    padding: 0.75rem 0;
    margin-bottom: 1.5rem;
}

.breadcrumb {
    display: flex;
    align-items: center;
    list-style: none;
    margin: 0;
    padding: 0;
    font-size: 0.9rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
}

.breadcrumb-separator {
    margin: 0 0.5rem;
    color: #adb5bd;
    font-size: 0.8rem;
    font-weight: 400;
}

.breadcrumb-link {
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.9rem;
}

.breadcrumb-link:hover {
    color: #0056b3;
    text-decoration: none;
}

.breadcrumb-link i {
    font-size: 0.85rem;
    color: #6c757d;
}

.breadcrumb-current {
    color: #495057;
    font-weight: 500;
    font-size: 0.9rem;
}

.breadcrumb-item.active .breadcrumb-current {
    color: #212529;
    font-weight: 500;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .breadcrumb {
        font-size: 0.8rem;
        flex-wrap: wrap;
    }
    
    .breadcrumb-separator {
        margin: 0 0.25rem;
    }
    
    .breadcrumb-link {
        gap: 0.125rem;
    }
    
    .breadcrumb-link i {
        font-size: 0.8rem;
    }
}
</style>
