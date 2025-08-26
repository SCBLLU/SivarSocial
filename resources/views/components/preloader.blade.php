<style>
    #preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #160a9e;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    #preloader video {
        max-width: 150px;
        width: 60%;
    }

    #preloader-footer {
        position: absolute;
        bottom: 30px;
        display: flex;
        justify-content: center;
        width: 100%;
    }

    #preloader-footer img {
        max-width: 150px;
    }

    .fade-out {
        opacity: 0;
        transition: opacity 1s ease;
        pointer-events: none;
    }
</style>

<div id="preloader" style="display:none;">
    <!-- Video centrado -->
    <video id="preloader-video" autoplay muted loop>
        <source src="{{ asset('video/logo-ani.mp4') }}" type="video/mp4">
        Tu navegador no soporta video.
    </video>

    <!-- Footer con imagen -->
    <div id="preloader-footer">
        <img src="{{ asset('img/fodder-loader.svg') }}" alt="Footer Logo">
    </div>
</div>

<script>
    const preloader = document.getElementById("preloader");
    const video = document.getElementById("preloader-video");

    // Detectar si la navegación fue reload (F5/Ctrl+R)
    let navType = "navigate";
    if (window.performance.getEntriesByType("navigation").length > 0) {
        navType = window.performance.getEntriesByType("navigation")[0].type;
    } else if (performance.navigation) {
        navType = performance.navigation.type === performance.navigation.TYPE_RELOAD ? "reload" : "navigate";
    }

    // Si es un refresh REAL, limpiamos sessionStorage
    if (navType === "reload") {
        sessionStorage.removeItem("preloaderShown");
    }

    // Mostrar solo si no se ha mostrado en esta navegación
    if (!sessionStorage.getItem("preloaderShown")) {
        preloader.style.display = "flex";

        window.addEventListener("load", () => {
            video.removeAttribute("loop");
            video.addEventListener("ended", () => {
                preloader.classList.add("fade-out");
                setTimeout(() => {
                    preloader.style.display = "none";
                }, 500);

                // Marcamos como mostrado en esta navegación
                sessionStorage.setItem("preloaderShown", "true");
            });
        });
    }
</script>