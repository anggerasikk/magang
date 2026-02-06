<?php 
// video-profil.php
include 'header.php'; 
?>

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
            background: linear-gradient(15deg, #13547a 0%, #80d0c7 100%);
            padding: 40px 0 30px 0;
            margin-top: 0;
            color: white;
            position: relative;
            overflow: hidden;
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

        .page-header .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        
        .page-header .breadcrumb-item.active {
            color: white;
        }
        
        .page-header .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.8);
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

    /* ===== VIDEO CONTAINER ENHANCED ===== */
    .video-container {
        background: white;
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 40px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        transition: var(--transition);
        border: 1px solid rgba(255, 255, 255, 0.8);
        position: relative;
        overflow: hidden;
    }

    .video-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
    }

    .video-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    }

    .video-wrapper {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 15px;
        margin-bottom: 30px;
        background: #000;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .video-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 15px;
    }

    .video-title {
        color: var(--dark-color);
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 2.2rem;
        margin-bottom: 20px;
        line-height: 1.3;
    }

    .video-description {
        color: var(--gray-color);
        font-size: 1.1rem;
        line-height: 1.7;
        margin-bottom: 25px;
    }

    .video-description ul {
        padding-left: 20px;
        margin: 15px 0;
    }

    .video-description li {
        margin-bottom: 10px;
        position: relative;
        padding-left: 10px;
    }

    .video-description li::before {
        content: '•';
        color: var(--secondary-color);
        font-weight: bold;
        position: absolute;
        left: -10px;
    }

    .video-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
        margin: 25px 0;
        padding: 20px;
        background: var(--light-color);
        border-radius: 12px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--gray-color);
        font-size: 0.95rem;
    }

    .meta-item i {
        color: var(--secondary-color);
        font-size: 1.2rem;
    }

    /* ===== SOCIAL SHARE ENHANCED ===== */
    .social-share {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-top: 30px;
        padding-top: 25px;
        border-top: 2px solid #f0f0f0;
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

    .share-btn.facebook {
        background: linear-gradient(135deg, #3b5998, #2d4373);
    }

    .share-btn.twitter {
        background: linear-gradient(135deg, #1da1f2, #0c85d0);
    }

    .share-btn.whatsapp {
        background: linear-gradient(135deg, #25d366, #128c7e);
    }

    .share-btn.link {
        background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
    }

    /* ===== VIDEO LIST ENHANCED ===== */
    .video-list-container {
        background: white;
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 40px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .video-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        margin-top: 30px;
    }

    @media (max-width: 768px) {
        .video-list {
            grid-template-columns: 1fr;
        }
    }

    .video-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        transition: var(--transition);
        cursor: pointer;
        border: 1px solid #eaeaea;
        height: 100%;
        position: relative;
    }

    .video-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        z-index: 2;
    }

    .video-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        border-color: var(--secondary-color);
    }

    .video-thumbnail {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%;
        overflow: hidden;
        background: #000;
    }

    .video-thumbnail img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .video-card:hover .video-thumbnail img {
        transform: scale(1.1);
    }

    .play-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(19, 84, 122, 0.9), rgba(128, 208, 199, 0.9));
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: var(--transition);
    }

    .video-card:hover .play-overlay {
        opacity: 1;
    }

    .play-icon {
        width: 70px;
        height: 70px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 2rem;
        transition: var(--transition);
    }

    .video-card:hover .play-icon {
        transform: scale(1.1);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .video-card-content {
        padding: 25px;
    }

    .video-card-title {
        color: var(--dark-color);
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 15px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .video-card-desc {
        color: var(--gray-color);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .video-card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
        color: var(--gray-color);
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
    }

    .video-card-duration {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
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

    /* ===== CONTACT INFO ENHANCED ===== */
    .contact-info {
        background: linear-gradient(135deg, var(--dark-color), var(--primary-color));
        color: white;
        border-radius: 20px;
        padding: 35px 30px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(19, 84, 122, 0.25);
    }

    .contact-info::before {
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

    .contact-info h4 {
        color: white;
        font-size: 1.4rem;
        margin-bottom: 25px;
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .contact-info h4 i {
        color: var(--secondary-color);
    }

    .contact-detail {
        margin-bottom: 20px;
        position: relative;
        z-index: 2;
    }

    .contact-detail:last-child {
        margin-bottom: 0;
    }

    .contact-detail h5 {
        color: white;
        font-size: 1rem;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .contact-detail p {
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    .contact-detail a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        transition: var(--transition);
    }

    .contact-detail a:hover {
        color: white;
        text-decoration: underline;
    }

    /* ===== PORTAL LINKS ENHANCED ===== */
    .portal-links {
        background: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .portal-link-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 12px;
        background: var(--light-color);
        transition: var(--transition);
        cursor: pointer;
    }

    .portal-link-item:last-child {
        margin-bottom: 0;
    }

    .portal-link-item:hover {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        transform: translateX(10px);
    }

    .portal-link-item:hover i,
    .portal-link-item:hover span {
        color: white !important;
    }

    .portal-link-item i {
        color: var(--secondary-color);
        margin-right: 15px;
        font-size: 1.3rem;
        transition: var(--transition);
    }

    .portal-link-item span {
        color: var(--primary-color);
        font-weight: 600;
        flex: 1;
        transition: var(--transition);
    }

    .portal-link-item a {
        color: var(--p-color);
        text-decoration: none;
        display: flex;
        align-items: center;
        width: 100%;
    }

    /* ===== DOWNLOAD BUTTONS ===== */
    .sidebar-menu .btn {
        padding: 15px;
        border-radius: 12px;
        font-weight: 600;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .sidebar-menu .custom-btn {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
    }

    .sidebar-menu .custom-btn:hover {
        background: linear-gradient(135deg, var(--dark-color), var(--primary-color));
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(19, 84, 122, 0.3);
    }

    .sidebar-menu .custom-border-btn {
        background: white;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }

    .sidebar-menu .custom-border-btn:hover {
        background: var(--light-color);
        border-color: var(--secondary-color);
        transform: translateY(-3px);
    }

    /* ===== VIDEO MODAL ENHANCED ===== */
    .video-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .video-modal.active {
        display: flex;
        opacity: 1;
    }

    .modal-content {
        width: 90%;
        max-width: 1000px;
        position: relative;
        transform: translateY(20px);
        transition: transform 0.4s ease;
    }

    .video-modal.active .modal-content {
        transform: translateY(0);
    }

    .modal-video-wrapper {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }

    .modal-video-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    .close-modal {
        position: absolute;
        top: -50px;
        right: 0;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        font-size: 2.5rem;
        cursor: pointer;
        transition: var(--transition);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
    }

    .close-modal:hover {
        color: var(--secondary-color);
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
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

    .video-card, .sidebar-menu, .video-container, .video-list-container {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .video-card:nth-child(1) { animation-delay: 0.1s; }
    .video-card:nth-child(2) { animation-delay: 0.2s; }
    .video-card:nth-child(3) { animation-delay: 0.3s; }

    /* ===== NOTIFICATION STYLES ===== */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        padding: 15px 25px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        z-index: 9999;
        transform: translateX(100%);
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
        min-width: 300px;
        border-left: 4px solid var(--primary-color);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .notification.show {
        transform: translateX(0);
        opacity: 1;
    }

    .notification.success {
        border-left-color: #28a745;
    }

    .notification.info {
        border-left-color: var(--primary-color);
    }

    .notification i {
        font-size: 1.5rem;
    }

    .notification.success i {
        color: #28a745;
    }

    .notification.info i {
        color: var(--primary-color);
    }

    .notification span {
        color: #333;
        font-weight: 500;
        flex: 1;
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

    /* ===== RESPONSIVE DESIGN ===== */
    @media (max-width: 1200px) {
        .page-header h1 {
            font-size: 3rem;
        }
        
        .video-list {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
    }

    @media (max-width: 992px) {
        .page-header {
            padding: 60px 0 40px;
        }
        
        .page-header h1 {
            font-size: 2.5rem;
        }
        
        .video-title {
            font-size: 1.8rem;
        }
        
        .video-list {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 50px 0 30px;
        }
        
        .page-header h1 {
            font-size: 2rem;
        }
        
        .video-container,
        .video-list-container,
        .sidebar-menu,
        .contact-info,
        .portal-links {
            padding: 25px;
        }
        
        .video-title {
            font-size: 1.6rem;
        }
        
        .video-list {
            grid-template-columns: 1fr;
        }
        
        .video-meta {
            flex-direction: column;
            gap: 15px;
        }
        
        .social-share {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .share-buttons {
            justify-content: flex-start;
        }
        
        .modal-content {
            width: 95%;
        }
    }

    @media (max-width: 576px) {
        .page-header h1 {
            font-size: 1.8rem;
        }
        
        .section-title {
            font-size: 1.5rem;
        }
        
        .video-container,
        .video-list-container,
        .sidebar-menu,
        .contact-info,
        .portal-links {
            padding: 20px;
        }
        
        .video-title {
            font-size: 1.4rem;
        }
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
                    <h1 class="text-white">VIDEO PROFIL JDIH</h1>
                    <p class="subtitle">Sekretariat Dewan Perwakilan Rakyat Daerah Kota Surakarta</p>
                                        <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="#">Tentang Kami</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Video Profil</li>
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
                    
                    <!-- VIDEO UTAMA -->
                    <div class="video-container">
                        <div class="video-wrapper">
                            <iframe src="https://www.youtube.com/embed/rko4gjE72Gc" 
                                    title="Video Profil JDIH DPRD Kota Surakarta" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                            </iframe>
                        </div>
                        
                        <h2 class="video-title">JDIH DPRD Kota Surakarta - Video Profil</h2>
                        
                        <div class="video-description">
                            <p>
                                Video profil ini memperkenalkan Jaringan Dokumentasi dan Informasi Hukum (JDIH) 
                                Sekretariat DPRD Kota Surakarta. Dalam video ini, Anda akan melihat:
                            </p>
                            <ul>
                                <li>Visi dan misi JDIH DPRD Kota Surakarta</li>
                                <li>Fasilitas dan infrastruktur yang dimiliki</li>
                                <li>Proses pengelolaan produk hukum</li>
                                <li>Pelayanan yang diberikan kepada masyarakat</li>
                                <li>Testimoni dari pengguna layanan JDIH</li>
                            </ul>
                            <p>
                                Video ini diharapkan dapat memberikan gambaran yang komprehensif tentang 
                                peran dan fungsi JDIH dalam mendukung transparansi dan aksesibilitas 
                                informasi hukum di Kota Surakarta.
                            </p>
                        </div>
                        
                        <div class="video-meta">
                            <div class="meta-item">
                                <i class="bi bi-calendar"></i>
                                <span>Dipublikasikan: 15 Januari 2024</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-clock"></i>
                                <span>Durasi: 5:42 menit</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-eye"></i>
                                <span>Ditonton: 1.245 kali</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-youtube"></i>
                                <span>YouTube</span>
                            </div>
                        </div>
                        
                        <!-- SOCIAL SHARE -->
                        <div class="social-share">
                            <div class="share-label">Bagikan video ini:</div>
                            <div class="share-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=https://youtu.be/rko4gjE72Gc" 
                                   target="_blank" 
                                   class="share-btn facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=https://youtu.be/rko4gjE72Gc&text=Video%20Profil%20JDIH%20DPRD%20Kota%20Surakarta" 
                                   target="_blank" 
                                   class="share-btn twitter">
                                    <i class="bi bi-twitter"></i>
                                </a>
                                <a href="https://wa.me/?text=Tonton%20Video%20Profil%20JDIH%20DPRD%20Kota%20Surakarta%3A%20https%3A%2F%2Fyoutu.be%2Frko4gjE72Gc" 
                                   target="_blank" 
                                   class="share-btn whatsapp">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                <a href="#" 
                                   onclick="copyVideoLink()" 
                                   class="share-btn link" id="copyLinkBtn">
                                    <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- VIDEO LAINNYA -->
                    <div class="video-list-container">
                        <h3 class="section-title">Video Lainnya</h3>
                        
                        <div class="video-list">
                            <!-- Video 2 - Tutorial -->
                            <div class="video-card" data-video-id="-aAbLqOKyd8" data-video-title="Bincang-Bincang Informasi Hukum JDIH Kota Surakarta (BIJAKS)">
                                <div class="video-thumbnail">
                                    <img src="https://img.youtube.com/vi/-aAbLqOKyd8/maxresdefault.jpg" alt="Bincang-Bincang Informasi Hukum JDIH Kota Surakarta (BIJAKS" onerror="this.src='https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'">
                                    <div class="play-overlay">
                                        <div class="play-icon">
                                            <i class="bi bi-play-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="video-card-content">
                                    <h4 class="video-card-title">Bincang-Bincang Informasi Hukum JDIH Kota Surakarta (BIJAKS)</h4>
                                    <p class="video-card-desc">Panduan lengkap cara menggunakan website JDIH DPRD Kota Surakarta untuk mencari dan mengakses produk hukum.</p>
                                    <div class="video-card-meta">
                                        <span><i class="bi bi-clock"></i> 7:30</span>
                                        <span class="video-card-duration">Tutorial</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Video 3 - Proses Legislasi -->
                            <div class="video-card" data-video-id="B5XrqNA9FJ8" data-video-title="JDIH Surakarta mempersembahkan film dengan judul "Rebyek"">
                                <div class="video-thumbnail">
                                    <img src="https://img.youtube.com/vi/B5XrqNA9FJ8/maxresdefault.jpg" alt="JDIH Surakarta mempersembahkan film dengan judul "Rebyek"" onerror="this.src='https://images.unsplash.com/photo-1589391886085-8b6b0ac72a1a?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'">
                                    <div class="play-overlay">
                                        <div class="play-icon">
                                            <i class="bi bi-play-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="video-card-content">
                                    <h4 class="video-card-title">JDIH Surakarta mempersembahkan film dengan judul "Rebyek"</h4>
                                    <p class="video-card-desc">Proses pembentukan peraturan daerah mulai dari pengajuan hingga pengesahan. Pelajari tahapan-tahapannya.</p>
                                    <div class="video-card-meta">
                                        <span><i class="bi bi-clock"></i> 9:15</span>
                                        <span class="video-card-duration">Edukasi</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Video 4 - Wawancara -->
                            <div class="video-card" data-video-id="IQ9IijAz8Ow" data-video-title="Website JDIH Surakarta">
                                <div class="video-thumbnail">
                                    <img src="https://img.youtube.com/vi/IQ9IijAz8Ow/maxresdefault.jpg" alt="Wawancara dengan Kepala JDIH" onerror="this.src='https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'">
                                    <div class="play-overlay">
                                        <div class="play-icon">
                                            <i class="bi bi-play-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="video-card-content">
                                    <h4 class="video-card-title">Website JDIH</h4>
                                    <p class="video-card-desc">Wawancara eksklusif dengan Kepala JDIH DPRD Kota Surakarta tentang tantangan dan prestasi.</p>
                                    <div class="video-card-meta">
                                        <span><i class="bi bi-clock"></i> 12:45</span>
                                        <span class="video-card-duration">Wawancara</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    
                    <!-- MENU TERKAIT -->
                    <div class="sidebar-menu">
                        <h4><i class="bi bi-link-45deg"></i> Menu Terkait</h4>
                        <ul>
                            <li class="<?= (strpos($_SERVER['PHP_SELF'], 'Profil-JDIH') !== false) ? 'active' : '' ?>">
                                <a href="?page=profil-jdih">
                                    <i class="bi bi-building"></i>
                                    Profil JDIH
                                </a>
                            </li>
                            <li class="<?= (strpos($_SERVER['PHP_SELF'], 'struktur-organisasi') !== false) ? 'active' : '' ?>">
                                <a href="?page=struktur-organisasi">
                                    <i class="bi bi-diagram-3"></i>
                                    Struktur Organisasi
                                </a>
                            </li>
                            <li class="<?= (strpos($_SERVER['PHP_SELF'], 'alur-pengelolaan') !== false) ? 'active' : '' ?>">
                                <a href="?page=alur-pengelolaan">
                                    <i class="bi bi-diagram-2"></i>
                                    Alur Pengelolaan
                                </a>
                            </li>
                            <li class="<?= (strpos($_SERVER['PHP_SELF'], 'sop-pengelola') !== false) ? 'active' : '' ?>">
                                <a href="?page=sop-pengelola">
                                    <i class="bi bi-clipboard-check"></i>
                                    SOP Pengelola
                                </a>
                            </li>
                            <li class="<?= (strpos($_SERVER['PHP_SELF'], 'dasar-hukum') !== false) ? 'active' : '' ?>">
                                <a href="?page=dasar-hukum">
                                    <i class="bi bi-journal-text"></i>
                                    Dasar Hukum
                                </a>
                            </li>
                            <li class="<?= (strpos($_SERVER['PHP_SELF'], 'surat-keputusan') !== false) ? 'active' : '' ?>">
                                <a href="?page=surat-keputusan">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    Surat Keputusan
                                </a>
                            </li>
                            <li class="<?= (strpos($_SERVER['PHP_SELF'], 'video-profile') !== false) ? 'active' : '' ?>">
                                <a href="?page=video-profile">
                                    <i class="bi bi-play-circle"></i>
                                    Video Profile
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- VIDEO MODAL -->
    <div class="video-modal" id="videoModal">
        <div class="modal-content">
            <button class="close-modal" id="closeModal">&times;</button>
            <div class="modal-video-wrapper">
                <iframe id="modalVideoPlayer" 
                        src="" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>

<script>
    // Fungsi untuk copy link video
    function copyVideoLink() {
        const videoUrl = 'https://youtu.be/rko4gjE72Gc';
        navigator.clipboard.writeText(videoUrl).then(() => {
            showNotification('Link video telah disalin!', 'success');
            
            // Update button visual feedback
            const button = document.getElementById('copyLinkBtn');
            const originalHTML = button.innerHTML;
            const originalBackground = button.style.background;
            
            button.innerHTML = '<i class="bi bi-check"></i>';
            button.style.background = 'linear-gradient(135deg, #25d366, #128c7e)';
            
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.style.background = originalBackground;
            }, 2000);
        }).catch(err => {
            showNotification('Gagal menyalin link!', 'error');
        });
    }
    
    // Fungsi untuk membuka modal video
    function openVideoModal(videoId, videoTitle) {
        const modal = document.getElementById('videoModal');
        const player = document.getElementById('modalVideoPlayer');
        
        player.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1`;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        document.title = `${videoTitle} - JDIH DPRD Kota Surakarta`;
    }
    
    // Fungsi untuk menutup modal video
    function closeVideoModal() {
        const modal = document.getElementById('videoModal');
        const player = document.getElementById('modalVideoPlayer');
        
        modal.classList.remove('active');
        player.src = '';
        document.body.style.overflow = 'auto';
        document.title = 'Video Profil - JDIH DPRD Kota Surakarta';
    }
    
    // Fungsi untuk menampilkan notifikasi
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-info-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
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
    
    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        // Add click event to video cards
        const videoCards = document.querySelectorAll('.video-card');
        videoCards.forEach(card => {
            card.addEventListener('click', (e) => {
                const videoId = card.dataset.videoId;
                const videoTitle = card.dataset.videoTitle;
                
                if (videoId) {
                    openVideoModal(videoId, videoTitle);
                }
            });
        });
        
        // Add click event to close modal button
        const closeModalBtn = document.getElementById('closeModal');
        closeModalBtn.addEventListener('click', closeVideoModal);
        
        // Close modal when clicking outside
        const videoModal = document.getElementById('videoModal');
        videoModal.addEventListener('click', (e) => {
            if (e.target === videoModal) {
                closeVideoModal();
            }
        });
        
        // Close modal with ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && videoModal.classList.contains('active')) {
                closeVideoModal();
            }
        });
        
        // Simulate view count update
        setTimeout(() => {
            const viewCountElement = document.querySelector('.meta-item:nth-child(3) span');
            if (viewCountElement) {
                const currentText = viewCountElement.textContent;
                const currentViews = parseInt(currentText.match(/\d+/)[0]);
                viewCountElement.textContent = `Ditonton: ${currentViews + 1} kali`;
            }
        }, 5000);
        
        // Add loading animation to download buttons
        const downloadButtons = document.querySelectorAll('.sidebar-menu .btn');
        downloadButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!this.href || this.href.includes('#')) {
                    e.preventDefault();
                    const originalHTML = this.innerHTML;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                    this.disabled = true;
                    
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.disabled = false;
                        showNotification('Download dimulai...', 'info');
                    }, 1500);
                }
            });
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
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
    });
</script>