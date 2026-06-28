<?php
$cssFile = __DIR__ . '/../frontend/assets/css/style.css';

$css = <<<CSS

/* ══════════════════════════════════════════════════════════
   🏠 Home Layout & Ads Feed
   ══════════════════════════════════════════════════════════ */
.home-container {
    max-width: 1400px;
    margin: 1.5rem auto;
    padding: 0 1.25rem;
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 1.5rem;
}

@media (max-width: 992px) {
    .home-container {
        grid-template-columns: 1fr;
    }
}

/* Sidebar Styling */
.sidebar-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 1.25rem;
    height: fit-content;
    position: sticky;
    top: 90px;
    box-shadow: var(--shadow-sm);
}
.sidebar-title {
    font-size: 1rem;
    font-weight: 800;
    color: var(--secondary);
    margin-bottom: 1rem;
    border-bottom: 2px solid var(--border-color);
    padding-bottom: 0.5rem;
}
.brand-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
    margin-bottom: 2rem;
}
.brand-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 0.5rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    background: var(--bg-color);
    color: var(--text-main);
    font-size: 0.75rem;
    font-weight: 700;
    transition: var(--transition);
}
.brand-item:hover, .brand-item.active {
    border-color: var(--primary);
    background: var(--primary-light);
    color: var(--primary);
}

.brand-list-all {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.brand-list-all-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: var(--radius-sm);
    color: var(--text-main);
    font-weight: 600;
    font-size: 0.9rem;
    transition: var(--transition);
}
.brand-list-all-item:hover, .brand-list-all-item.active {
    background: var(--primary-light);
    color: var(--primary);
    padding-right: 1.25rem;
}

/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 0.5rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.filter-tabs::-webkit-scrollbar { display: none; }
.filter-tab-btn {
    white-space: nowrap;
    padding: 0.5rem 1.25rem;
    border: 1px solid var(--border-color);
    border-radius: 50px;
    background: var(--card-bg);
    color: var(--text-muted);
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.filter-tab-btn:hover, .filter-tab-btn.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Horizontal Ad Feed Rows */
.ad-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.ad-row {
    display: flex;
    justify-content: space-between;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    overflow: hidden;
    padding: 1rem;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}
.ad-row:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--primary);
    transform: translateY(-2px);
}
.ad-row-main {
    display: flex;
    gap: 1.25rem;
    flex: 1;
}
.ad-row-thumb {
    width: 160px;
    height: 120px;
    object-fit: cover;
    border-radius: var(--radius-sm);
    flex-shrink: 0;
}
.ad-row-content {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}
.ad-row-title {
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--text-main);
    margin: 0 0 0.5rem 0;
    line-height: 1.4;
}
.ad-row-meta {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-top: auto;
}
.ad-row-meta-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.ad-row-side {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: flex-start;
    padding-right: 1.5rem;
    border-right: 1px dashed var(--border-color);
    min-width: 140px;
}
.ad-row-price {
    font-size: 1.25rem;
    font-weight: 900;
    color: var(--secondary);
    margin-bottom: 0.5rem;
}
.ad-row-city {
    font-size: 0.85rem;
    color: var(--text-muted);
    font-weight: 700;
}
.badge-pinned {
    background-color: rgba(228, 168, 53, 0.15);
    color: #d29a2a;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 800;
    margin-left: 6px;
    vertical-align: middle;
}

@media (max-width: 768px) {
    .ad-row {
        flex-direction: column;
        padding: 0;
    }
    .ad-row-main {
        flex-direction: column;
        gap: 0;
    }
    .ad-row-thumb {
        width: 100%;
        height: 200px;
        border-radius: var(--radius-md) var(--radius-md) 0 0;
    }
    .ad-row-content {
        padding: 1rem;
    }
    .ad-row-side {
        border-right: none;
        border-top: 1px dashed var(--border-color);
        padding: 1rem;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        background: var(--bg-color);
    }
    .ad-row-price {
        margin-bottom: 0;
    }
}
CSS;

file_put_contents($cssFile, $css, FILE_APPEND);
echo "CSS appended successfully!";
