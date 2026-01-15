<?php include 'header.php'; ?>

<style>
    /* ===== VARIABLES & OVERRIDES ===== */
    :root {
        --primary-color: #13547a;
        --secondary-color: #80d0c7;
        --accent-color: #ff6b6b;
        --dark-color: #1a365d;
        --light-color: #f0f8ff;
        --gray-color: #6c757d;
        --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
        font-family: 'Open Sans', sans-serif;
    }

    /* ===== HEADER SECTION ENHANCED ===== */
    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-color) 100%);
        padding: 80px 0 50px;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,208C672,213,768,203,864,181.3C960,160,1056,128,1152,128C1248,128,1344,160,1392,176L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
        background-size: cover;
        background-position: bottom;
    }

    .page-header h1 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        font-size: 3.5rem;
        margin-bottom: 15px;
        position: relative;
        background: linear-gradient(to right, #ffffff, #e0f7ff);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }

    .page-header .subtitle {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 25px;
        position: relative;
        max-width: 600px;
        line-height: 1.6;
    }

    .breadcrumb {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        padding: 12px 25px !important;
        display: inline-block;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.9) !important;
        text-decoration: none;
        transition: var(--transition);
    }

    .breadcrumb-item a:hover {
        color: white !important;
        transform: translateX(3px);
    }

    .breadcrumb-item.active {
        color: white !important;
        font-weight: 600;
    }

    /* ===== MAIN CONTENT ===== */
    .main-content {
        padding: 40px 0 80px;
        position: relative;
    }

    .section-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 30px;
        position: relative;
        padding-bottom: 15px;
        font-size: 1.8rem;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 70px;
        height: 4px;
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        border-radius: 2px;
    }

    .section-title::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 80px;
        width: 20px;
        height: 4px;
        background: var(--accent-color);
        border-radius: 2px;
    }

    /* ===== FEATURED ARTICLE ENHANCED ===== */
    .featured-article {
        background: linear-gradient(135deg, var(--dark-color) 0%, var(--primary-color) 100%);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 50px;
        position: relative;
        box-shadow: 0 20px 40px rgba(19, 84, 122, 0.25);
        transition: var(--transition);
    }

    .featured-article:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(19, 84, 122, 0.35);
    }

    .featured-content {
        padding: 50px;
        position: relative;
        z-index: 2;
    }

    .featured-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: var(--transition);
    }

    .featured-badge:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
    }

    .featured-title {
        color: white;
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 2.5rem;
        margin-bottom: 20px;
        line-height: 1.3;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .featured-excerpt {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.2rem;
        line-height: 1.6;
        margin-bottom: 30px;
        max-width: 800px;
    }

    .featured-meta {
        display: flex;
        gap: 30px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .featured-meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.95rem;
    }

    .featured-meta-item i {
        color: var(--secondary-color);
        font-size: 1.2rem;
    }

    .custom-btn.custom-btn-bg-white {
        background: white;
        color: var(--primary-color);
        border: none;
        padding: 14px 35px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
    }

    .custom-btn.custom-btn-bg-white:hover {
        background: var(--secondary-color);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(255, 255, 255, 0.3);
    }

    /* ===== ARTICLE CONTAINER ===== */
    .article-container {
        background: white;
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: var(--transition);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .article-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    /* ===== CATEGORY CLOUD ENHANCED ===== */
    .category-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .category-item {
        background: var(--light-color);
        color: var(--primary-color);
        padding: 10px 20px;
        border-radius: 50px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: var(--transition);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .category-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.7s;
    }

    .category-item:hover::before {
        left: 100%;
    }

    .category-item:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--secondary-color);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.2);
    }

    .category-item.active {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-color: var(--secondary-color);
        font-weight: 600;
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
    }

    /* ===== ARTICLE GRID ENHANCED ===== */
    .article-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
        margin-top: 30px;
    }

    .article-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        transition: var(--transition);
        text-decoration: none;
        display: block;
        border: 1px solid #eaeaea;
        height: 100%;
        position: relative;
    }

    .article-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        z-index: 2;
    }

    .article-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        border-color: var(--secondary-color);
    }

    .article-category {
        position: absolute;
        top: 20px;
        left: 20px;
        background: rgba(19, 84, 122, 0.9);
        backdrop-filter: blur(10px);
        color: white;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .article-image {
        height: 220px;
        width: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .article-card:hover .article-image {
        transform: scale(1.1);
    }

    .article-content {
        padding: 25px;
    }

    .article-title {
        color: var(--dark-color);
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 1.3rem;
        margin-bottom: 15px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .article-excerpt {
        color: var(--gray-color);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .article-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
        margin-top: 15px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--gray-color);
        font-size: 0.85rem;
    }

    .meta-item i {
        color: var(--secondary-color);
    }

    /* ===== PAGINATION ENHANCED ===== */
    .pagination-container {
        margin-top: 40px;
    }

    .pagination {
        justify-content: center;
        gap: 5px;
    }

    .page-item .page-link {
        border: 2px solid transparent;
        background: white;
        color: var(--primary-color);
        border-radius: 10px;
        padding: 10px 18px;
        font-weight: 500;
        transition: var(--transition);
        margin: 0 2px;
    }

    .page-item .page-link:hover {
        background: var(--light-color);
        border-color: var(--secondary-color);
        transform: translateY(-3px);
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-color: var(--secondary-color);
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
    }

    /* ===== ARTICLE DETAIL ENHANCED ===== */
    .article-detail {
        background: white;
        border-radius: 20px;
        padding: 50px;
        margin-top: 40px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .article-detail::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color), var(--accent-color));
    }

    .article-header {
        margin-bottom: 40px;
        padding-bottom: 30px;
        border-bottom: 2px solid #f0f0f0;
    }

    .article-detail-title {
        color: var(--dark-color);
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        font-size: 2.8rem;
        margin-bottom: 20px;
        line-height: 1.2;
    }

    .article-detail-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
        align-items: center;
    }

    .article-author {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: var(--light-color);
        border-radius: 15px;
        flex: 1;
        min-width: 250px;
    }

    .author-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.3rem;
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--gray-color);
        font-size: 0.95rem;
        background: var(--light-color);
        padding: 10px 20px;
        border-radius: 10px;
    }

    .meta-item i {
        color: var(--secondary-color);
        font-size: 1.1rem;
    }

    .article-body {
        font-size: 1.15rem;
        line-height: 1.8;
        color: #444;
    }

    .article-body p {
        margin-bottom: 25px;
    }

    .article-body h2 {
        color: var(--primary-color);
        margin: 40px 0 25px;
        font-size: 2rem;
        padding-bottom: 10px;
        border-bottom: 3px solid var(--light-color);
    }

    .article-body h3 {
        color: var(--dark-color);
        margin: 35px 0 20px;
        font-size: 1.6rem;
    }

    .article-body blockquote {
        border-left: 5px solid var(--secondary-color);
        padding: 25px 35px;
        margin: 35px 0;
        background: linear-gradient(135deg, #f8fdff, #e8f7ff);
        border-radius: 0 15px 15px 0;
        font-style: italic;
        color: var(--primary-color);
        font-size: 1.2rem;
        position: relative;
        overflow: hidden;
    }

    .article-body blockquote::before {
        content: '"';
        position: absolute;
        top: -30px;
        left: 10px;
        font-size: 120px;
        color: rgba(19, 84, 122, 0.1);
        font-family: Georgia, serif;
    }

    .article-body img {
        max-width: 100%;
        height: auto;
        border-radius: 15px;
        margin: 35px 0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        transition: var(--transition);
    }

    .article-body img:hover {
        transform: scale(1.01);
    }

    .article-footer {
        margin-top: 50px;
        padding-top: 30px;
        border-top: 2px solid #f0f0f0;
    }

    .article-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 35px;
    }

    .tag {
        background: var(--light-color);
        color: var(--primary-color);
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.9rem;
        text-decoration: none;
        transition: var(--transition);
        border: 2px solid transparent;
        font-weight: 500;
    }

    .tag:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--secondary-color);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.2);
    }

    .share-article {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .share-label {
        color: var(--primary-color);
        font-weight: 600;
        font-size: 1.1rem;
    }

    .share-buttons {
        display: flex;
        gap: 15px;
    }

    .share-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: var(--transition);
        font-size: 1.2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .share-btn:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    /* ===== SIDEBAR ENHANCED ===== */
    .sidebar-menu {
        background: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.8);
        transition: var(--transition);
    }

    .sidebar-menu:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    }

    .sidebar-menu h4 {
        color: var(--dark-color);
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 1.3rem;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid var(--light-color);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar-menu h4 i {
        color: var(--secondary-color);
        font-size: 1.4rem;
    }

    .sidebar-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu ul li {
        margin-bottom: 10px;
        position: relative;
    }

    .sidebar-menu ul li a {
        color: var(--gray-color);
        text-decoration: none;
        padding: 12px 15px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: var(--transition);
        font-weight: 500;
    }

    .sidebar-menu ul li a:hover {
        background: var(--light-color);
        color: var(--primary-color);
        transform: translateX(10px);
    }

    .sidebar-menu ul li.active a {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        font-weight: 600;
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
    }

    /* ===== SEARCH BOX ENHANCED ===== */
    .search-box {
        background: linear-gradient(135deg, var(--light-color), white);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        border: 2px solid #e8f7ff;
        position: relative;
        overflow: hidden;
    }

    .search-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
    }

    .search-form .input-group {
        margin-bottom: 20px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .search-form input {
        border: 2px solid #e0e0e0;
        border-right: none;
        padding: 15px 20px;
        font-size: 1rem;
    }

    .search-form input:focus {
        border-color: var(--secondary-color);
        box-shadow: none;
    }

    .search-form .btn-search {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 15px 25px;
        font-weight: 600;
        transition: var(--transition);
    }

    .search-form .btn-search:hover {
        background: var(--dark-color);
        transform: scale(1.05);
    }

    .form-check {
        margin-bottom: 10px;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* ===== NEWSLETTER BOX ENHANCED ===== */
    .newsletter-box {
        background: linear-gradient(135deg, var(--dark-color), var(--primary-color));
        color: white;
        border-radius: 20px;
        padding: 35px 30px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(19, 84, 122, 0.25);
    }

    .newsletter-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
        background-size: cover;
        background-position: bottom;
    }

    .newsletter-box h4 {
        color: white;
        font-size: 1.4rem;
        margin-bottom: 15px;
        position: relative;
        z-index: 2;
    }

    .newsletter-box p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.95rem;
        margin-bottom: 25px;
        line-height: 1.6;
        position: relative;
        z-index: 2;
    }

    .newsletter-box .form-control {
        background: rgba(255, 255, 255, 0.95);
        border: none;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 15px;
        position: relative;
        z-index: 2;
        transition: var(--transition);
    }

    .newsletter-box .form-control:focus {
        background: white;
        box-shadow: 0 5px 20px rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    .newsletter-box .form-select {
        background: rgba(255, 255, 255, 0.95);
        border: none;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        position: relative;
        z-index: 2;
    }

    .newsletter-box .btn-subscribe {
        background: white;
        color: var(--primary-color);
        font-weight: 600;
        padding: 15px;
        border-radius: 12px;
        border: none;
        width: 100%;
        transition: var(--transition);
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .newsletter-box .btn-subscribe:hover {
        background: var(--secondary-color);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(255, 255, 255, 0.2);
    }

    /* ===== POPULAR ARTICLES ENHANCED ===== */
    .popular-article-item {
        display: flex;
        gap: 15px;
        padding: 20px 0;
        border-bottom: 1px solid #f0f0f0;
        transition: var(--transition);
    }

    .popular-article-item:hover {
        transform: translateX(10px);
        border-bottom-color: var(--secondary-color);
    }

    .popular-article-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .popular-article-item:first-child {
        padding-top: 0;
    }

    .popular-article-number {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 5px 15px rgba(19, 84, 122, 0.3);
    }

    .popular-article-content h5 {
        color: var(--dark-color);
        font-size: 1rem;
        margin-bottom: 8px;
        line-height: 1.4;
        font-weight: 600;
        transition: var(--transition);
    }

    .popular-article-item:hover .popular-article-content h5 {
        color: var(--primary-color);
    }

    .popular-article-meta {
        font-size: 0.85rem;
        color: var(--gray-color);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .popular-article-meta i {
        color: var(--secondary-color);
    }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .article-card, .sidebar-menu, .search-box, .newsletter-box {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .article-card:nth-child(1) { animation-delay: 0.1s; }
    .article-card:nth-child(2) { animation-delay: 0.2s; }
    .article-card:nth-child(3) { animation-delay: 0.3s; }
    .article-card:nth-child(4) { animation-delay: 0.4s; }
    .article-card:nth-child(5) { animation-delay: 0.5s; }
    .article-card:nth-child(6) { animation-delay: 0.6s; }

    /* ===== RESPONSIVE DESIGN ===== */
    @media (max-width: 1200px) {
        .page-header h1 {
            font-size: 3rem;
        }
        
        .article-grid {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        }
    }

    @media (max-width: 992px) {
        .page-header {
            padding: 60px 0 40px;
        }
        
        .page-header h1 {
            font-size: 2.5rem;
        }
        
        .featured-title {
            font-size: 2rem;
        }
        
        .article-detail-title {
            font-size: 2.2rem;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 50px 0 30px;
        }
        
        .page-header h1 {
            font-size: 2rem;
        }
        
        .featured-content {
            padding: 30px;
        }
        
        .featured-title {
            font-size: 1.6rem;
        }
        
        .article-grid {
            grid-template-columns: 1fr;
        }
        
        .article-detail {
            padding: 30px;
        }
        
        .article-detail-title {
            font-size: 1.8rem;
        }
        
        .article-detail-meta {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
        
        .article-author {
            min-width: 100%;
        }
        
        .featured-meta {
            flex-direction: column;
            gap: 15px;
        }
    }

    @media (max-width: 576px) {
        .article-container,
        .sidebar-menu,
        .search-box,
        .newsletter-box {
            padding: 25px;
        }
        
        .section-title {
            font-size: 1.5rem;
        }
        
        .category-item {
            padding: 8px 15px;
            font-size: 0.85rem;
        }
        
        .share-buttons {
            justify-content: center;
        }
    }

    /* ===== SCROLL TO TOP ===== */
    .scroll-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 1.2rem;
        box-shadow: 0 5px 20px rgba(19, 84, 122, 0.4);
        z-index: 1000;
        transition: var(--transition);
        opacity: 0;
        visibility: hidden;
    }

    .scroll-to-top.active {
        opacity: 1;
        visibility: visible;
    }

    .scroll-to-top:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 10px 30px rgba(19, 84, 122, 0.6);
    }
</style>

<!-- SCROLL TO TOP BUTTON -->
<a href="#top" class="scroll-to-top" id="scrollToTop">
    <i class="bi bi-chevron-up"></i>
</a>

    <!-- HEADER SECTION -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-white">ARTIKEL HUKUM</h1>
                    <p class="subtitle">Kumpulan Artikel dan Publikasi Ilmiah Hukum dari DPRD Kota Surakarta</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Artikel Hukum</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="main-content">
        <div class="container">
            <div class="row">
                
                <!-- KONTEN UTAMA -->
                <div class="col-lg-8 mb-4 mb-lg-0">
                    
                    <!-- ARTIKEL UTAMA/TERBARU -->
                    <div class="featured-article">
                        <div class="featured-content">
                            <span class="featured-badge">Artikel Utama</span>
                            <h2 class="featured-title">Reformasi Legislasi Daerah: Strategi Peningkatan Kualitas Produk Hukum di Kota Surakarta</h2>
                            <p class="featured-excerpt">
                                Analisis komprehensif terhadap proses legislasi daerah dan strategi inovatif untuk meningkatkan kualitas produk hukum yang dihasilkan DPRD Kota Surakarta dalam mendukung pembangunan berkelanjutan.
                            </p>
                            <div class="featured-meta">
                                <div class="featured-meta-item">
                                    <i class="bi bi-person"></i>
                                    <span>Dr. H. Bambang Sutrisno, SH., MH.</span>
                                </div>
                                <div class="featured-meta-item">
                                    <i class="bi bi-calendar"></i>
                                    <span>15 Januari 2024</span>
                                </div>
                                <div class="featured-meta-item">
                                    <i class="bi bi-eye"></i>
                                    <span>1.245x dibaca</span>
                                </div>
                            </div>
                            <a href="#artikel-detail" class="custom-btn custom-btn-bg-white">
                                <i class="bi bi-book"></i>
                                Baca Artikel Lengkap
                            </a>
                        </div>
                    </div>
                    
                    <!-- FILTER KATEGORI -->
                    <div class="article-container">
                        <h3 class="section-title">Filter Artikel</h3>
                        <div class="category-cloud">
                            <a href="#" class="category-item active">Semua</a>
                            <a href="#" class="category-item">Legislasi</a>
                            <a href="#" class="category-item">Pengawasan</a>
                            <a href="#" class="category-item">Anggaran</a>
                            <a href="#" class="category-item">Pemerintahan</a>
                            <a href="#" class="category-item">Hukum Tata Negara</a>
                            <a href="#" class="category-item">Hukum Administrasi</a>
                            <a href="#" class="category-item">Hukum Perdata</a>
                            <a href="#" class="category-item">Hukum Pidana</a>
                            <a href="#" class="category-item">Kajian Hukum</a>
                        </div>
                    </div>
                    
                    <!-- DAFTAR ARTIKEL -->
                    <div class="article-container">
                        <h3 class="section-title">Artikel Terbaru</h3>
                        
                        <div class="article-grid">
                            <!-- Artikel 1 -->
                            <a href="#artikel-detail" class="article-card">
                                <span class="article-category">Legislasi</span>
                                <img src="https://images.unsplash.com/photo-1589391886085-8b6b0ac72a1a?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Proses Legislasi" 
                                     class="article-image">
                                <div class="article-content">
                                    <h3 class="article-title">Optimalisasi Fungsi Pengawasan DPRD dalam Pelaksanaan APBD Kota Surakarta</h3>
                                    <p class="article-excerpt">
                                        Kajian mendalam tentang mekanisme pengawasan DPRD terhadap pelaksanaan Anggaran Pendapatan dan Belanja Daerah serta strategi peningkatan efektivitasnya.
                                    </p>
                                    <div class="article-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-person"></i>
                                            <span>Drs. Agus Setiawan, M.Si.</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>10 Jan 2024</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Artikel 2 -->
                            <a href="#artikel-detail" class="article-card">
                                <span class="article-category">Hukum Tata Negara</span>
                                <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Hukum Tata Negara" 
                                     class="article-image">
                                <div class="article-content">
                                    <h3 class="article-title">Implikasi Otonomi Daerah terhadap Kewenangan Legislasi DPRD Kota Surakarta</h3>
                                    <p class="article-excerpt">
                                        Analisis yuridis tentang batas kewenangan legislasi DPRD dalam konteks otonomi daerah dan harmonisasi dengan peraturan perundang-undangan nasional.
                                    </p>
                                    <div class="article-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-person"></i>
                                            <span>Prof. Dr. Sri Wahyuni, SH., MH.</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>5 Jan 2024</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Artikel 3 -->
                            <a href="#artikel-detail" class="article-card">
                                <span class="article-category">Pengawasan</span>
                                <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Pengelolaan Anggaran" 
                                     class="article-image">
                                <div class="article-content">
                                    <h3 class="article-title">Transparansi dan Akuntabilitas dalam Pengelolaan Keuangan Daerah</h3>
                                    <p class="article-excerpt">
                                        Studi tentang implementasi prinsip transparansi dan akuntabilitas dalam pengelolaan keuangan daerah di Kota Surakarta serta peran DPRD.
                                    </p>
                                    <div class="article-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-person"></i>
                                            <span>Ir. Budi Santoso, M.Acc.</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>28 Des 2023</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Artikel 4 -->
                            <a href="#artikel-detail" class="article-card">
                                <span class="article-category">Pemerintahan</span>
                                <img src="https://images.unsplash.com/photo-1542744095-fcf48d80b0fd?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Tata Kelola Pemerintahan" 
                                     class="article-image">
                                <div class="article-content">
                                    <h3 class="article-title">Inovasi Tata Kelola Pemerintahan Berbasis Digital di Era Society 5.0</h3>
                                    <p class="article-excerpt">
                                        Kajian tentang transformasi digital dalam tata kelola pemerintahan daerah dan implikasinya terhadap fungsi legislasi DPRD.
                                    </p>
                                    <div class="article-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-person"></i>
                                            <span>Dr. Maya Indriati, S.Si., M.T.</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>20 Des 2023</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Artikel 5 -->
                            <a href="#artikel-detail" class="article-card">
                                <span class="article-category">Hukum Administrasi</span>
                                <img src="https://images.unsplash.com/photo-1553877522-43269d4ea984?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Hukum Administrasi" 
                                     class="article-image">
                                <div class="article-content">
                                    <h3 class="article-title">Perlindungan Hukum terhadap Pelayanan Publik di Kota Surakarta</h3>
                                    <p class="article-excerpt">
                                        Analisis hukum administrasi tentang mekanisme perlindungan hukum bagi masyarakat dalam penerimaan pelayanan publik.
                                    </p>
                                    <div class="article-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-person"></i>
                                            <span>Hendra Wijaya, SH., LL.M.</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>15 Des 2023</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Artikel 6 -->
                            <a href="#artikel-detail" class="article-card">
                                <span class="article-category">Kajian Hukum</span>
                                <img src="https://images.unsplash.com/photo-1518717202715-9fa9d099f58a?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                                     alt="Kajian Hukum" 
                                     class="article-image">
                                <div class="article-content">
                                    <h3 class="article-title">Analisis Yuridis tentang Pemekaran Wilayah di Jawa Tengah</h3>
                                    <p class="article-excerpt">
                                        Studi komparatif tentang implikasi hukum pemekaran wilayah terhadap struktur pemerintahan daerah dan distribusi kewenangan.
                                    </p>
                                    <div class="article-meta">
                                        <div class="meta-item">
                                            <i class="bi bi-person"></i>
                                            <span>Prof. Dr. Ahmad Fauzi, SH., MH.</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="bi bi-calendar"></i>
                                            <span>10 Des 2023</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- PAGINATION -->
                        <div class="pagination-container">
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- DETAIL ARTIKEL (Contoh) -->
                    <div id="artikel-detail" class="article-detail">
                        <div class="article-header">
                            <h1 class="article-detail-title">Reformasi Legislasi Daerah: Strategi Peningkatan Kualitas Produk Hukum di Kota Surakarta</h1>
                            <div class="article-detail-meta">
                                <div class="article-author">
                                    <div class="author-avatar">BS</div>
                                    <div>
                                        <div class="author-name">Dr. H. Bambang Sutrisno, SH., MH.</div>
                                        <div class="author-title">Ketua Komisi Hukum DPRD Kota Surakarta</div>
                                    </div>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-calendar"></i>
                                    <span>Dipublikasikan: 15 Januari 2024</span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-eye"></i>
                                    <span>1.245x dibaca</span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-clock"></i>
                                    <span>Waktu baca: 8 menit</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="article-body">
                            <img src="https://images.unsplash.com/photo-1589391886085-8b6b0ac72a1a?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" 
                                 alt="Reformasi Legislasi" 
                                 class="img-fluid">
                            <p>Dalam era otonomi daerah yang semakin berkembang, fungsi legislasi Dewan Perwakilan Rakyat Daerah (DPRD) memiliki peran strategis dalam pembentukan peraturan daerah yang berkualitas. Kota Surakarta sebagai kota budaya dan pendidikan telah menunjukkan komitmennya dalam melakukan reformasi legislasi untuk mendukung pembangunan berkelanjutan.</p>
                            
                            <h2>Latar Belakang Reformasi Legislasi</h2>
                            <p>Reformasi legislasi daerah menjadi kebutuhan mendesak seiring dengan kompleksitas permasalahan pembangunan daerah. Data menunjukkan bahwa dalam lima tahun terakhir, DPRD Kota Surakarta telah menghasilkan 45 peraturan daerah, dengan tingkat implementasi mencapai 85%. Namun, tantangan masih ditemui dalam harmonisasi dengan peraturan pusat dan efektivitas implementasi di lapangan.</p>
                            
                            <blockquote>
                                "Kualitas produk hukum daerah tidak hanya diukur dari aspek yuridis formal, tetapi juga dari aspek sosiologis dan filosofis dalam memenuhi kebutuhan masyarakat."
                            </blockquote>
                            
                            <h2>Strategi Peningkatan Kualitas</h2>
                            <p>Beberapa strategi yang telah diterapkan DPRD Kota Surakarta meliputi:</p>
                            <ol>
                                <li><strong>Penguatan Kapasitas Legislator:</strong> Program pelatihan dan sertifikasi anggota DPRD dalam teknik legislasi.</li>
                                <li><strong>Partisipasi Publik yang Inklusif:</strong> Menerapkan model konsultasi publik berbasis digital dan tatap muka.</li>
                                <li><strong>Kajian Akademis Mendalam:</strong> Setiap rancangan peraturan daerah wajib dilengkapi dengan naskah akademik yang komprehensif.</li>
                                <li><strong>Harmonisasi Vertikal dan Horizontal:</strong> Mekanisme pemeriksaan harmonisasi dengan peraturan di atasnya dan sejajar.</li>
                            </ol>
                            
                            <h2>Implementasi dan Hasil</h2>
                            <p>Implementasi reformasi legislasi telah menunjukkan hasil yang signifikan. Indeks Kualitas Peraturan Daerah (IKPD) Kota Surakarta meningkat dari 75,2 pada tahun 2020 menjadi 88,5 pada tahun 2023. Selain itu, tingkat partisipasi masyarakat dalam proses legislasi meningkat 40% melalui platform digital yang disediakan.</p>
                            
                            <h2>Rekomendasi dan Ke Depan</h2>
                            <p>Untuk terus meningkatkan kualitas legislasi, diperlukan:</p>
                            <ul>
                                <li>Penguatan sistem monitoring dan evaluasi implementasi peraturan daerah</li>
                                <li>Integrasi teknologi dalam seluruh proses legislasi</li>
                                <li>Kerjasama dengan akademisi dan praktisi hukum</li>
                                <li>Penganggaran yang memadai untuk kegiatan legislasi</li>
                            </ul>
                            
                            <p>Reformasi legislasi daerah merupakan proses berkelanjutan yang memerlukan komitmen semua pemangku kepentingan. DPRD Kota Surakarta bertekad untuk terus meningkatkan kualitas produk hukum dalam rangka mewujudkan tata kelola pemerintahan yang baik dan pembangunan berkelanjutan.</p>
                        </div>
                        
                        <div class="article-footer">
                            <div class="article-tags">
                                <a href="#" class="tag">#LegislasiDaerah</a>
                                <a href="#" class="tag">#ReformasiHukum</a>
                                <a href="#" class="tag">#DPRDSurakarta</a>
                                <a href="#" class="tag">#ProdukHukum</a>
                                <a href="#" class="tag">#TataKelolaPemerintahan</a>
                            </div>
                            
                            <div class="share-article">
                                <div class="share-label">Bagikan Artikel:</div>
                                <div class="share-buttons">
                                    <a href="#" class="share-btn facebook">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                    <a href="#" class="share-btn twitter">
                                        <i class="bi bi-twitter"></i>
                                    </a>
                                    <a href="#" class="share-btn whatsapp">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                    <a href="#" class="share-btn link" onclick="copyToClipboard()">
                                        <i class="bi bi-link-45deg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    
                    <!-- PENCARIAN -->
                    <div class="search-box">
                        <h4>Cari Artikel</h4>
                        <form class="search-form">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Kata kunci..." aria-label="Search">
                                <button class="btn btn-search" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="searchTitle">
                                <label class="form-check-label" for="searchTitle">
                                    Cari dalam judul saja
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="searchContent">
                                <label class="form-check-label" for="searchContent">
                                    Cari dalam konten artikel
                                </label>
                            </div>
                        </form>
                    </div>
                    
                    <!-- KATEGORI -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-folder"></i> Kategori Artikel</h4>
                        <ul>
                            <li class="active"><a href="#"><i class="bi bi-chevron-right"></i> Semua Kategori</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Legislasi (45)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Pengawasan (32)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Anggaran (28)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Pemerintahan (24)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Hukum Tata Negara (19)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Hukum Administrasi (15)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Hukum Perdata (12)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Hukum Pidana (8)</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Kajian Hukum (21)</a></li>
                        </ul>
                    </div>
                    
                    <!-- ARTIKEL POPULER -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-fire"></i> Artikel Terpopuler</h4>
                        <div class="popular-article-item">
                            <div class="popular-article-number">1</div>
                            <div class="popular-article-content">
                                <h5>Digitalisasi Pelayanan Legislasi di Era Society 5.0</h5>
                                <div class="popular-article-meta">
                                    <span><i class="bi bi-eye"></i> 2,543</span>
                                    <span><i class="bi bi-calendar"></i> 20 Nov 2023</span>
                                </div>
                            </div>
                        </div>
                        <div class="popular-article-item">
                            <div class="popular-article-number">2</div>
                            <div class="popular-article-content">
                                <h5>Peran DPRD dalam Pengawasan Anggaran Responsif Gender</h5>
                                <div class="popular-article-meta">
                                    <span><i class="bi bi-eye"></i> 1,987</span>
                                    <span><i class="bi bi-calendar"></i> 5 Des 2023</span>
                                </div>
                            </div>
                        </div>
                        <div class="popular-article-item">
                            <div class="popular-article-number">3</div>
                            <div class="popular-article-content">
                                <h5>Harmonisasi Perda dengan UU Cipta Kerja</h5>
                                <div class="popular-article-meta">
                                    <span><i class="bi bi-eye"></i> 1,756</span>
                                    <span><i class="bi bi-calendar"></i> 12 Des 2023</span>
                                </div>
                            </div>
                        </div>
                        <div class="popular-article-item">
                            <div class="popular-article-number">4</div>
                            <div class="popular-article-content">
                                <h5>Strategi Pencegahan Korupsi di Lingkungan Pemerintah Daerah</h5>
                                <div class="popular-article-meta">
                                    <span><i class="bi bi-eye"></i> 1,543</span>
                                    <span><i class="bi bi-calendar"></i> 18 Des 2023</span>
                                </div>
                            </div>
                        </div>
                        <div class="popular-article-item">
                            <div class="popular-article-number">5</div>
                            <div class="popular-article-content">
                                <h5>Implementasi Prinsip Good Governance dalam Pembentukan Perda</h5>
                                <div class="popular-article-meta">
                                    <span><i class="bi bi-eye"></i> 1,432</span>
                                    <span><i class="bi bi-calendar"></i> 25 Des 2023</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- NEWSLETTER -->
                    <div class="newsletter-box">
                        <h4>Berlangganan Newsletter</h4>
                        <p>Dapatkan artikel hukum terbaru langsung ke email Anda. Gratis!</p>
                        <form>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Email Anda">
                            </div>
                            <div class="mb-3">
                                <select class="form-select">
                                    <option selected>Pilih Kategori Minat</option>
                                    <option value="1">Legislasi dan Perundangan</option>
                                    <option value="2">Pengawasan dan Anggaran</option>
                                    <option value="3">Hukum Tata Negara</option>
                                    <option value="4">Hukum Administrasi</option>
                                    <option value="5">Semua Kategori</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-subscribe">
                                <i class="bi bi-envelope"></i> Berlangganan
                            </button>
                        </form>
                    </div>
                    
                    <!-- DOWNLOAD -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-download"></i> Download Materi</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-file-earmark-pdf"></i> Panduan Teknik Legislasi (PDF)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-word"></i> Template Naskah Akademik (DOC)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-ppt"></i> Presentasi Reformasi Legislasi (PPT)</a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-zip"></i> Kumpulan Artikel Lengkap (ZIP)</a></li>
                        </ul>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>

<script>
    // Fungsi untuk copy link artikel
    function copyToClipboard() {
        const currentUrl = window.location.href;
        navigator.clipboard.writeText(currentUrl).then(() => {
            // Sweet notification instead of alert
            showNotification('Link artikel telah disalin!', 'success');
        });
    }
    
    // Fungsi untuk filter kategori
    document.querySelectorAll('.category-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Hapus active class dari semua item
            document.querySelectorAll('.category-item').forEach(el => {
                el.classList.remove('active');
            });
            
            // Tambah active class ke item yang diklik
            this.classList.add('active');
            
            // Simulasi filter artikel
            const category = this.textContent.trim();
            showNotification(`Memfilter artikel berdasarkan kategori: ${category}`, 'info');
        });
    });
    
    // Fungsi pencarian
    document.querySelector('.search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const keyword = this.querySelector('input[type="text"]').value;
        const searchTitle = this.querySelector('#searchTitle').checked;
        const searchContent = this.querySelector('#searchContent').checked;
        
        if (!keyword.trim()) {
            showNotification('Masukkan kata kunci pencarian!', 'warning');
            return;
        }
        
        showNotification(`Mencari artikel dengan kata kunci: "${keyword}"`, 'info');
    });
    
    // Fungsi berlangganan newsletter
    document.querySelector('.newsletter-box form').addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        const category = this.querySelector('select').value;
        
        if (!email) {
            showNotification('Masukkan alamat email!', 'warning');
            return;
        }
        
        showNotification(`Terima kasih telah berlangganan newsletter! Konfirmasi akan dikirim ke ${email}`, 'success');
        this.reset();
    });
    
    // Smooth scroll untuk link artikel
    document.querySelectorAll('a[href^="#artikel-detail"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Scroll to top functionality
    const scrollToTopBtn = document.getElementById('scrollToTop');
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.classList.add('active');
        } else {
            scrollToTopBtn.classList.remove('active');
        }
    });
    
    scrollToTopBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="bi ${type === 'success' ? 'bi-check-circle' : type === 'warning' ? 'bi-exclamation-triangle' : 'bi-info-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
    
    // Add notification styles
    const style = document.createElement('style');
    style.textContent = `
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            z-index: 9999;
            transform: translateX(100%);
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
            min-width: 300px;
            border-left: 4px solid var(--primary-color);
        }
        
        .notification.show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .notification.success {
            border-left-color: #28a745;
        }
        
        .notification.warning {
            border-left-color: #ffc107;
        }
        
        .notification.info {
            border-left-color: var(--primary-color);
        }
        
        .notification-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .notification-content i {
            font-size: 1.2rem;
        }
        
        .notification.success .notification-content i {
            color: #28a745;
        }
        
        .notification.warning .notification-content i {
            color: #ffc107;
        }
        
        .notification.info .notification-content i {
            color: var(--primary-color);
        }
        
        .notification-content span {
            color: #333;
            font-weight: 500;
        }
    `;
    document.head.appendChild(style);
    
    // Add hover effects to all interactive elements
    document.addEventListener('DOMContentLoaded', () => {
        // Add hover effect to article cards
        const articleCards = document.querySelectorAll('.article-card');
        articleCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                const category = card.querySelector('.article-category');
                if (category) {
                    category.style.transform = 'translateY(-3px)';
                    category.style.boxShadow = '0 8px 20px rgba(19, 84, 122, 0.3)';
                }
            });
            
            card.addEventListener('mouseleave', () => {
                const category = card.querySelector('.article-category');
                if (category) {
                    category.style.transform = 'translateY(0)';
                    category.style.boxShadow = 'none';
                }
            });
        });
        
        // Add loading animation to buttons
        const buttons = document.querySelectorAll('button, .btn, .custom-btn, .category-item, .tag');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (this.tagName === 'BUTTON' || this.classList.contains('btn')) {
                    const originalHTML = this.innerHTML;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                    this.disabled = true;
                    
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.disabled = false;
                    }, 1500);
                }
            });
        });
    });
</script>