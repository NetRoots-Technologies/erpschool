<style>
    /* Full-screen loader overlay */
    .page-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.4);
        /* semi-transparent dark overlay */
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2000;
        backdrop-filter: blur(1px);
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transition: opacity 0.15s ease, visibility 0.15s ease;
    }

    .page-loader.fade {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    .page-loader__spinner {
        width: 48px;
        height: 48px;
        border: 5px solid rgba(255, 255, 255, 0.6);
        border-top-color: transparent;
        border-radius: 50%;
        animation: page-loader-spin 0.8s linear infinite;
    }

    @keyframes page-loader-spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
<!-- Footer opened -->
<div class="main-footer ht-45">
    <div class="container-fluid pd-t-0 ht-100p">
        <span> Copyright © 2024 <a href="https://www.netrootstech.com/" target="_blank" class="text-primary">Netroots
                Technologies</a>. All rights reserved.</span>
    </div>
</div>

<div class="page-loader fade">
    <div class="page-loader__spinner"></div>
</div>
<script>
    function loader(status) {
        console.log('company select class');
        if (status === 'show') {
            console.log('show loader');
            $(".page-loader").removeClass("fade");
        }
        if (status === 'hide') {
            console.log('hide loader');
            $(".page-loader").addClass("fade");
        }
    }
</script>
<!-- Footer closed -->