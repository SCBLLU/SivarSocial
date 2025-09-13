/**
 * Global User Status System
 * Utiliza la infraestructura de Pusher de Chatify para mostrar estado activo en toda la app
 */

window.GlobalStatus = {
    pusher: null,
    activeStatusChannel: null,
    initialized: false,

    /**
     * Inicializar el sistema de estado global
     * Este método debe ser llamado en todas las páginas donde queremos mostrar estado activo
     */
    init: function (chatifyConfig) {
        console.log("=== GlobalStatus.init called ===");
        console.log("initialized:", this.initialized);
        console.log("chatifyConfig:", chatifyConfig);

        if (this.initialized || !chatifyConfig) {
            console.log("Skipping init - already initialized or no config");
            return;
        }

        try {
            console.log("Checking for existing Pusher...");

            // Reutilizar el Pusher de Chatify si existe, si no, crear uno nuevo
            if (window.pusher) {
                console.log("Using existing Pusher instance");
                this.pusher = window.pusher;
            } else {
                console.log(
                    "Creating new Pusher instance with config:",
                    chatifyConfig.pusher
                );

                if (typeof Pusher === "undefined") {
                    console.error("Pusher library not loaded!");
                    return;
                }

                Pusher.logToConsole = chatifyConfig.pusher.debug;
                this.pusher = new Pusher(chatifyConfig.pusher.key, {
                    encrypted: chatifyConfig.pusher.options.encrypted,
                    cluster: chatifyConfig.pusher.options.cluster,
                    wsHost: chatifyConfig.pusher.options.host,
                    wsPort: chatifyConfig.pusher.options.port,
                    wssPort: chatifyConfig.pusher.options.port,
                    forceTLS: chatifyConfig.pusher.options.useTLS,
                    authEndpoint: chatifyConfig.pusherAuthEndpoint,
                    auth: {
                        headers: {
                            "X-CSRF-TOKEN":
                                chatifyConfig.csrfToken ||
                                document
                                    .querySelector('meta[name="csrf-token"]')
                                    ?.getAttribute("content"),
                            "X-Requested-With": "XMLHttpRequest",
                        },
                    },
                });

                console.log("Pusher config used:", {
                    authEndpoint: chatifyConfig.pusherAuthEndpoint,
                    csrfToken: chatifyConfig.csrfToken ? "presente" : "ausente",
                });

                console.log("New Pusher instance created:", this.pusher);
            }

            console.log("Subscribing to presence-activeStatus channel...");

            // Suscribirse al canal de estado activo
            this.activeStatusChannel = this.pusher.subscribe(
                "presence-activeStatus"
            );

            console.log(
                "Channel subscription initiated:",
                this.activeStatusChannel
            );

            // Manejar errores de suscripción
            this.activeStatusChannel.bind(
                "pusher:subscription_error",
                (error) => {
                    console.error("GlobalStatus: Subscription error:", error);
                    console.log(
                        "Trying to use public channel instead of presence channel..."
                    );

                    // Intentar usar un canal público como fallback
                    this.fallbackToPublicChannel();
                }
            );

            // Cuando se conecta exitosamente, mostrar usuarios online
            this.activeStatusChannel.bind(
                "pusher:subscription_succeeded",
                (members) => {
                    console.log(
                        "GlobalStatus: Connected to presence channel",
                        members
                    );
                    this.clearAllActiveStatus();
                    if (members && members.each) {
                        members.each((member) => {
                            if (member.id != window.authUserId) {
                                this.setActiveStatus(true, member.id);
                            }
                        });
                    }
                }
            );

            // Cuando alguien se conecta
            this.activeStatusChannel.bind("pusher:member_added", (member) => {
                console.log("GlobalStatus: User came online", member);
                this.setActiveStatus(true, member.id);
            });

            // Cuando alguien se desconecta
            this.activeStatusChannel.bind("pusher:member_removed", (member) => {
                console.log("GlobalStatus: User went offline", member);
                this.setActiveStatus(false, member.id);
            });

            this.initialized = true;
            console.log("GlobalStatus: Initialized successfully");
        } catch (error) {
            console.error("GlobalStatus: Error initializing", error);
        }
    },

    /**
     * Fallback a canal público si el de presencia no funciona
     */
    fallbackToPublicChannel: function () {
        console.log("Using public channel as fallback...");

        try {
            // Usar un canal público simple
            this.activeStatusChannel = this.pusher.subscribe(
                "user-status-updates"
            );

            this.activeStatusChannel.bind("user-online", (data) => {
                console.log("User came online:", data);
                if (data.user_id != window.authUserId) {
                    this.setActiveStatus(true, data.user_id);
                }
            });

            this.activeStatusChannel.bind("user-offline", (data) => {
                console.log("User went offline:", data);
                if (data.user_id != window.authUserId) {
                    this.setActiveStatus(false, data.user_id);
                }
            });

            console.log("Fallback channel setup complete");
        } catch (error) {
            console.error("Fallback channel setup failed:", error);
        }
    },

    /**
     * Establecer estado activo para un usuario
     */
    setActiveStatus: function (isOnline, userId) {
        // Buscar elementos con data-user-id usando tanto jQuery como vanilla JS
        let userElements;

        if (typeof $ !== "undefined") {
            userElements = $(`[data-user-id="${userId}"]`);
        } else {
            userElements = document.querySelectorAll(
                `[data-user-id="${userId}"]`
            );
        }

        console.log(
            `Setting status for user ${userId}:`,
            isOnline ? "ONLINE" : "OFFLINE"
        );
        console.log(
            "Found elements:",
            userElements.length || userElements.size || 0
        );

        if (isOnline) {
            // Función para agregar círculo a un elemento
            const addCircleToElement = (element) => {
                // Remover círculos existentes
                const existingCircles =
                    element.querySelectorAll(".status-circle");
                existingCircles.forEach((circle) => circle.remove());

                // Buscar la imagen del avatar
                const avatar = element.querySelector("img");

                if (avatar) {
                    console.log("Found avatar for user", userId, avatar);

                    // Crear círculo verde
                    const statusCircle = document.createElement("div");
                    statusCircle.className = "status-circle online-status";
                    statusCircle.style.cssText = `
                        position: absolute; 
                        width: 12px; 
                        height: 12px; 
                        background-color: #10B981; 
                        border: 2px solid white; 
                        border-radius: 50%; 
                        bottom: 0; 
                        right: 0; 
                        z-index: 10;
                        box-shadow: 0 0 4px rgba(0,0,0,0.2);
                    `;

                    // Asegurar que el contenedor padre tenga posición relativa
                    avatar.parentElement.style.position = "relative";
                    avatar.parentElement.appendChild(statusCircle);

                    console.log("Added status circle to user", userId);
                } else {
                    console.log("No avatar found for user", userId);
                }
            };

            if (typeof $ !== "undefined" && userElements.length) {
                userElements.each(function () {
                    addCircleToElement(this);
                });
            } else if (userElements.length) {
                userElements.forEach(addCircleToElement);
            }
        } else {
            // Remover círculos verdes
            const removeCircleFromElement = (element) => {
                const circles = element.querySelectorAll(".status-circle");
                circles.forEach((circle) => circle.remove());
            };

            if (typeof $ !== "undefined" && userElements.length) {
                userElements.each(function () {
                    removeCircleFromElement(this);
                });
            } else if (userElements.length) {
                userElements.forEach(removeCircleFromElement);
            }
        }
    },

    /**
     * Limpiar todos los estados activos
     */
    clearAllActiveStatus: function () {
        if (typeof $ !== "undefined") {
            $(".status-circle").remove();
        } else {
            const circles = document.querySelectorAll(".status-circle");
            circles.forEach((circle) => circle.remove());
        }
    },

    /**
     * Destruir el sistema (para limpieza)
     */
    destroy: function () {
        if (this.activeStatusChannel) {
            this.activeStatusChannel.unbind_all();
            this.pusher.unsubscribe("presence-activeStatus");
        }
        this.clearAllActiveStatus();
        this.initialized = false;
    },
};

/**
 * Auto-inicializar si la configuración de Chatify está disponible
 */
function initGlobalStatus() {
    console.log("=== GLOBAL STATUS DEBUG ===");
    console.log("jQuery loaded:", typeof $ !== "undefined");
    console.log(
        "window.chatify exists:",
        typeof window.chatify !== "undefined"
    );
    console.log("window.authUserId:", window.authUserId);

    if (window.chatify) {
        console.log("Chatify config:", window.chatify);
    }

    // Esperar un poco para que Chatify se cargue si estamos en la página del chat
    setTimeout(function () {
        if (window.chatify && window.authUserId) {
            console.log("GlobalStatus: Auto-initializing with Chatify config");
            window.GlobalStatus.init(window.chatify);
        } else {
            console.error(
                "GlobalStatus: Missing config - chatify:",
                window.chatify,
                "authUserId:",
                window.authUserId
            );
        }
    }, 1000);
}

// Verificar si jQuery está disponible
if (typeof $ !== "undefined") {
    $(document).ready(initGlobalStatus);
} else if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initGlobalStatus);
} else {
    // DOM ya está cargado
    initGlobalStatus();
}
