/**
 *-------------------------------------------------------------
 * Global variables
 *-------------------------------------------------------------
 */
var messenger,
    typingTimeout,
    typingNow = 0,
    temporaryMsgId = 0,
    messengerColor,
    dark_mode,
    messages_page = 1;

/* esto sobrescribe al "Conectarse" a SIVAR CHAT*/
const messagesContainer = $(".messenger-messagingView .m-body"),
    messengerTitleDefault = $(".messenger-headTitle").html(),
    messageInputContainer = $(".messenger-sendCard"),
    messageInput = $("#message-form .m-send"),
    auth_id = $("meta[name=url]").attr("data-user"),
    url = $("meta[name=url]").attr("content"),
    messengerTheme = $("meta[name=messenger-theme]").attr("content"),
    defaultMessengerColor = $("meta[name=messenger-color]").attr("content"),
    csrfToken = $('meta[name="csrf-token"]').attr("content");

const getMessengerId = () => $("meta[name=id]").attr("content");
const setMessengerId = (id) => $("meta[name=id]").attr("content", id);

/**
 *-------------------------------------------------------------
 * Pusher initialization
 *-------------------------------------------------------------
 */
Pusher.logToConsole = chatify.pusher.debug;
const pusher = new Pusher(chatify.pusher.key, {
    encrypted: chatify.pusher.options.encrypted,
    cluster: chatify.pusher.options.cluster,
    wsHost: chatify.pusher.options.host,
    wsPort: chatify.pusher.options.port,
    wssPort: chatify.pusher.options.port,
    forceTLS: chatify.pusher.options.useTLS,
    authEndpoint: chatify.pusherAuthEndpoint,
    auth: {
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
    },
});
/**
 *-------------------------------------------------------------
 * Re-usable methods
 *-------------------------------------------------------------
 */
const escapeHtml = (unsafe) => {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
};
function actionOnScroll(selector, callback, topScroll = false) {
    $(selector).on("scroll", function () {
        let element = $(this).get(0);
        const condition = topScroll
            ? element.scrollTop == 0
            : element.scrollTop + element.clientHeight >= element.scrollHeight;
        if (condition) {
            callback();
        }
    });
}
function routerPush(title, url) {
    $("meta[name=url]").attr("content", url);
    return window.history.pushState({}, title || document.title, url);
}
function updateSelectedContact(user_id) {
    $(document).find(".messenger-list-item").removeClass("m-list-active");
    $(document)
        .find(
            ".messenger-list-item[data-contact=" +
                (user_id || getMessengerId()) +
                "]"
        )
        .addClass("m-list-active");
}

// Force real profile image on all avatars
function forceProfileAvatars() {
    // List and favorites
    $(".messenger-list-item .avatar, .messenger-favorites .avatar").each(
        function () {
            var img = $(this).attr("data-imagen");
            if (img) {
                $(this).css("background-image", 'url("' + img + '")');
            }
        }
    );
    // Info panel
    $(".messenger-infoView .avatar").each(function () {
        var img = $(this).attr("data-imagen");
        if (img) {
            $(this).css("background-image", 'url("' + img + '")');
        }
    });
    // Header avatar
    $(".header-avatar").each(function () {
        var img = $(this).attr("data-imagen");
        if (img) {
            $(this).css("background-image", 'url("' + img + '")');
        }
    });
}

$(document).on(
    "DOMSubtreeModified",
    ".listOfContacts, .messenger-favorites, .messenger-infoView",
    function () {
        forceProfileAvatars();
    }
);

/**
 *-------------------------------------------------------------
 * Global Templates
 *-------------------------------------------------------------
 */
// Loading svg
function loadingSVG(size = "25px", className = "", style = "") {
    return `
<svg style="${style}" class="loadingSVG ${className}" xmlns="http://www.w3.org/2000/svg" width="${size}" height="${size}" viewBox="0 0 40 40" stroke="#ffffff">
<g fill="none" fill-rule="evenodd">
<g transform="translate(2 2)" stroke-width="3">
<circle stroke-opacity=".1" cx="18" cy="18" r="18"></circle>
<path d="M36 18c0-9.94-8.06-18-18-18" transform="rotate(349.311 18 18)">
<animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur=".8s" repeatCount="indefinite"></animateTransform>
</path>
</g>
</g>
</svg>
`;
}
function loadingWithContainer(className) {
    return `<div class="${className}" style="text-align:center;padding:15px">${loadingSVG(
        "25px",
        "",
        "margin:auto"
    )}</div>`;
}

// loading placeholder for users list item
function listItemLoading(items) {
    let template = "";
    for (let i = 0; i < items; i++) {
        template += `
<div class="loadingPlaceholder">
<div class="loadingPlaceholder-wrapper">
<div class="loadingPlaceholder-body">
<table class="loadingPlaceholder-header">
<tr>
<td style="width: 45px;"><div class="loadingPlaceholder-avatar"></div></td>
<td>
<div class="loadingPlaceholder-name"></div>
<div class="loadingPlaceholder-date"></div>
</td>
</tr>
</table>
</div>
</div>
</div>
`;
    }
    return template;
}

// loading placeholder for avatars
function avatarLoading(items) {
    let template = "";
    for (let i = 0; i < items; i++) {
        template += `
<div class="loadingPlaceholder">
<div class="loadingPlaceholder-wrapper">
<div class="loadingPlaceholder-body">
<table class="loadingPlaceholder-header">
<tr>
<td style="width: 45px;">
<div class="loadingPlaceholder-avatar" style="margin: 2px;"></div>
</td>
</tr>
</table>
</div>
</div>
</div>
`;
    }
    return template;
}

// While sending a message, show this temporary message card.
function sendTempMessageCard(message, id) {
    return `
 <div class="message-card mc-sender" data-id="${id}">
     <div class="message-card-content">
         <div class="message">
             ${message}
             <sub>
                 <span class="far fa-clock"></span>
             </sub>
         </div>
     </div>
 </div>
`;
}
// upload image preview card.
function attachmentTemplate(fileType, fileName, imgURL = null) {
    if (fileType != "image") {
        return (
            `
 <div class="attachment-preview">
     <span class="fas fa-times cancel"></span>
     <p style="padding:0px 30px;"><span class="fas fa-file"></span> ` +
            escapeHtml(fileName) +
            `</p>
 </div>
`
        );
    } else {
        return (
            `
<div class="attachment-preview">
 <span class="fas fa-times cancel"></span>
 <div class="image-file chat-image" style="background-image: url('` +
            imgURL +
            `');"></div>
 <p><span class="fas fa-file-image"></span> ` +
            escapeHtml(fileName) +
            `</p>
</div>
`
        );
    }
}

// Active Status Circle
function activeStatusCircle() {
    return `<span class="activeStatus"></span>`;
}

/**
 *-------------------------------------------------------------
 * Css Media Queries [For responsive design]
 *-------------------------------------------------------------
 */
$(window).resize(function () {
    cssMediaQueries();
});
function cssMediaQueries() {
    if (window.matchMedia("(min-width: 980px)").matches) {
        $(".messenger-listView").removeAttr("style");
    }
    // No need to set data-action attributes anymore since we handle visibility directly
}

/**
 *-------------------------------------------------------------
 * App Modal
 *-------------------------------------------------------------
 */
let app_modal = function ({
    show = true,
    name,
    data = 0,
    buttons = true,
    header = null,
    body = null,
}) {
    const modal = $(".app-modal[data-name=" + name + "]");
    // header
    header ? modal.find(".app-modal-header").html(header) : "";

    // body
    body ? modal.find(".app-modal-body").html(body) : "";

    // buttons
    buttons == true
        ? modal.find(".app-modal-footer").show()
        : modal.find(".app-modal-footer").hide();

    // show / hide
    if (show == true) {
        modal.show();
        $(".app-modal-card[data-name=" + name + "]").addClass("app-show-modal");
        $(".app-modal-card[data-name=" + name + "]").attr("data-modal", data);
    } else {
        modal.hide();
        $(".app-modal-card[data-name=" + name + "]").removeClass(
            "app-show-modal"
        );
        $(".app-modal-card[data-name=" + name + "]").attr("data-modal", data);
    }
};

/**
 *-------------------------------------------------------------
 * Slide to bottom on [action] - e.g. [message received, sent, loaded]
 *-------------------------------------------------------------
 */
function scrollToBottom(container) {
    $(container)
        .stop()
        .animate({
            scrollTop: $(container)[0].scrollHeight,
        });
}

/**
 *-------------------------------------------------------------
 * click and drag to scroll - function
 *-------------------------------------------------------------
 */
function hScroller(scroller) {
    const slider = document.querySelector(scroller);
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener("mousedown", (e) => {
        isDown = true;
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });
    slider.addEventListener("mouseleave", () => {
        isDown = false;
    });
    slider.addEventListener("mouseup", () => {
        isDown = false;
    });
    slider.addEventListener("mousemove", (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 1;
        slider.scrollLeft = scrollLeft - walk;
    });
}

/**
 *-------------------------------------------------------------
 * Disable/enable message form fields, messaging container...
 * on load info or if needed elsewhere.
 *
 * Default : true
 *-------------------------------------------------------------
 */
function disableOnLoad(disable = true) {
    if (disable) {
        // hide star button
        $(".add-to-favorite").hide();
        // hide send card
        $(".messenger-sendCard").hide();
        // add loading opacity to messages container
        messagesContainer.css("opacity", ".5");
        // disable message form fields
        messageInput.attr("readonly", "readonly");
        $("#message-form button").attr("disabled", "disabled");
        $(".upload-attachment").attr("disabled", "disabled");
    } else {
        // show star button
        if (getMessengerId() != auth_id) {
            $(".add-to-favorite").show();
        }
        // show send card
        $(".messenger-sendCard").show();
        // remove loading opacity to messages container
        messagesContainer.css("opacity", "1");
        // enable message form fields
        messageInput.removeAttr("readonly");
        $("#message-form button").removeAttr("disabled");
        $(".upload-attachment").removeAttr("disabled");
    }
}

/**
 *-------------------------------------------------------------
 * Error message card
 *-------------------------------------------------------------
 */
function errorMessageCard(id) {
    messagesContainer
        .find(".message-card[data-id=" + id + "]")
        .addClass("mc-error");
    messagesContainer
        .find(".message-card[data-id=" + id + "]")
        .find("svg.loadingSVG")
        .remove();
    messagesContainer
        .find(".message-card[data-id=" + id + "] p")
        .prepend('<span class="fas fa-exclamation-triangle"></span>');
}

/**
 *-------------------------------------------------------------
 * Fetch id data (user/group) and update the view
 *-------------------------------------------------------------
 */
function IDinfo(id) {
    // clear temporary message id
    temporaryMsgId = 0;
    // clear typing now
    typingNow = 0;
    // show loading bar
    NProgress.start();
    // disable message form
    disableOnLoad();
    if (messenger != 0) {
        // get shared photos
        getSharedPhotos(id);
        // Get info
        $.ajax({
            url: url + "/idInfo",
            method: "POST",
            data: { _token: csrfToken, id },
            dataType: "JSON",
            success: (data) => {
                if (!data?.fetch) {
                    NProgress.done();
                    NProgress.remove();
                    return;
                }

                // Get the correct avatar from the contact list item
                const contactItem = $(
                    `.messenger-list-item[data-contact=${id}]`
                );
                const correctAvatar = contactItem
                    .find(".avatar")
                    .attr("data-imagen");

                // Set avatar photo
                const avatarURL = correctAvatar
                    ? `url("${correctAvatar}")`
                    : `url("${data.user_avatar}")`;
                $(".messenger-infoView")
                    .find(".avatar")
                    .css("background-image", avatarURL);
                $(".header-avatar").css("background-image", avatarURL);

                // Show shared and actions
                $(".messenger-infoView-btns .delete-conversation").show();
                $(".messenger-infoView-btns .view-profile-btn").addClass(
                    "show"
                );
                $(".messenger-infoView-shared").show();
                // fetch messages
                fetchMessages(id, true);
                // focus on messaging input
                messageInput.focus();
                // update info in view
                $(".messenger-infoView .info-name").text(data.fetch.name);
                $(".m-header-messaging .user-name").text(data.fetch.name);
                // Update profile button with user username
                $(".messenger-infoView .view-profile-btn").attr(
                    "href",
                    `/${data.fetch.username}`
                );
                // Star status
                data.favorite > 0
                    ? $(".add-to-favorite").addClass("favorite")
                    : $(".add-to-favorite").removeClass("favorite");
                // form reset and focus
                $("#message-form").trigger("reset");
                cancelAttachment();
                messageInput.focus();

                // Update mutual followers list to remove unread badges for current conversation
                updateMutualFollowersList();
            },
            error: () => {
                console.error("Couldn't fetch user data!");
                // remove loading bar
                NProgress.done();
                NProgress.remove();
            },
        });
    } else {
        // remove loading bar
        NProgress.done();
        NProgress.remove();
    }
}

/**
 *-------------------------------------------------------------
 * Send message function
 *-------------------------------------------------------------
 */
function sendMessage() {
    temporaryMsgId += 1;
    let tempID = `temp_${temporaryMsgId}`;
    let hasFile = !!$(".upload-attachment").val();
    const inputValue = $.trim(messageInput.val());
    if (inputValue.length > 0 || hasFile) {
        const formData = new FormData($("#message-form")[0]);
        formData.append("id", getMessengerId());
        formData.append("temporaryMsgId", tempID);
        formData.append("_token", csrfToken);
        $.ajax({
            url: $("#message-form").attr("action"),
            method: "POST",
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            beforeSend: () => {
                // remove message hint
                $(".messages").find(".message-hint").hide();
                // append a temporary message card
                if (hasFile) {
                    messagesContainer
                        .find(".messages")
                        .append(
                            sendTempMessageCard(
                                inputValue + "\n" + loadingSVG("28px"),
                                tempID
                            )
                        );
                } else {
                    messagesContainer
                        .find(".messages")
                        .append(sendTempMessageCard(inputValue, tempID));
                }
                // scroll to bottom
                scrollToBottom(messagesContainer);
                messageInput.css({ height: "42px" });
                // form reset and focus
                $("#message-form").trigger("reset");
                cancelAttachment();
                messageInput.focus();
            },
            success: (data) => {
                if (data.error > 0) {
                    // message card error status
                    errorMessageCard(tempID);
                    console.error(data.error_msg);
                } else {
                    // update contact item
                    updateContactItem(getMessengerId());
                    // temporary message card
                    const tempMsgCardElement = messagesContainer.find(
                        `.message-card[data-id=${data.tempID}]`
                    );
                    // add the message card coming from the server before the temp-card
                    tempMsgCardElement.before(data.message);
                    // then, remove the temporary message card
                    tempMsgCardElement.remove();
                    // scroll to bottom
                    scrollToBottom(messagesContainer);
                    // send contact item updates
                    sendContactItemUpdates(true);
                }
            },
            error: () => {
                // message card error status
                errorMessageCard(tempID);
                // error log
                console.error(
                    "Failed sending the message! Please, check your server response."
                );
            },
        });
    }
    return false;
}

/**
 *-------------------------------------------------------------
 * Fetch messages from database
 *-------------------------------------------------------------
 */
let messagesPage = 1;
let noMoreMessages = false;
let messagesLoading = false;
function setMessagesLoading(loading = false) {
    if (!loading) {
        messagesContainer.find(".messages").find(".loading-messages").remove();
        NProgress.done();
        NProgress.remove();
    } else {
        messagesContainer
            .find(".messages")
            .prepend(loadingWithContainer("loading-messages"));
    }
    messagesLoading = loading;
}
function fetchMessages(id, newFetch = false) {
    if (newFetch) {
        messagesPage = 1;
        noMoreMessages = false;
    }
    if (messenger != 0 && !noMoreMessages && !messagesLoading) {
        const messagesElement = messagesContainer.find(".messages");
        setMessagesLoading(true);
        $.ajax({
            url: url + "/fetchMessages",
            method: "POST",
            data: {
                _token: csrfToken,
                id: id,
                page: messagesPage,
            },
            dataType: "JSON",
            success: (data) => {
                setMessagesLoading(false);
                if (messagesPage == 1) {
                    messagesElement.html(data.messages);
                    scrollToBottom(messagesContainer);
                } else {
                    const lastMsg = messagesElement.find(
                        messagesElement.find(".message-card")[0]
                    );
                    const curOffset =
                        lastMsg.offset().top - messagesContainer.scrollTop();
                    messagesElement.prepend(data.messages);
                    messagesContainer.scrollTop(
                        lastMsg.offset().top - curOffset
                    );
                }
                // NO marcar como visto automáticamente al cargar mensajes
                // Solo marcar como visto cuando el usuario realmente interactúa
                // trigger seen event - REMOVIDO para preservar contadores
                // makeSeen(true);

                // Pagination lock & messages page
                noMoreMessages = messagesPage >= data?.last_page;
                if (!noMoreMessages) messagesPage += 1;
                // Enable message form if messenger not = 0; means if data is valid
                if (messenger != 0) {
                    disableOnLoad(false);
                }
            },
            error: (error) => {
                setMessagesLoading(false);
                console.error(error);
            },
        });
    }
}

/**
 *-------------------------------------------------------------
 * Cancel file attached in the message.
 *-------------------------------------------------------------
 */
function cancelAttachment() {
    $(".messenger-sendCard").find(".attachment-preview").remove();
    $(".upload-attachment").replaceWith(
        $(".upload-attachment").val("").clone(true)
    );
}

/**
 *-------------------------------------------------------------
 * Pusher channels and event listening..
 *-------------------------------------------------------------
 */

// subscribe to the channel
const channelName = "private-chatify";
var channel = pusher.subscribe(`${channelName}.${auth_id}`);
var clientSendChannel;
var clientListenChannel;

function initClientChannel() {
    if (getMessengerId()) {
        clientSendChannel = pusher.subscribe(
            `${channelName}.${getMessengerId()}`
        );
        clientListenChannel = pusher.subscribe(`${channelName}.${auth_id}`);
    }
}
initClientChannel();

// Listen to messages, and append if data received
channel.bind("messaging", function (data) {
    if (data.from_id == getMessengerId() && data.to_id == auth_id) {
        $(".messages").find(".message-hint").remove();
        messagesContainer.find(".messages").append(data.message);
        scrollToBottom(messagesContainer);

        // Solo marcar como visto si el usuario está activamente en esta conversación
        // y la ventana está visible
        if (!document.hidden) {
            makeSeen(true);
            // remove unseen counter for the user from the contacts list
            $(".messenger-list-item[data-contact=" + getMessengerId() + "]")
                .find("tr>td>b")
                .remove();
        }
    }

    playNotificationSound(
        "new_message",
        !(data.from_id == getMessengerId() && data.to_id == auth_id)
    );
});

// listen to typing indicator
clientListenChannel.bind("client-typing", function (data) {
    if (data.from_id == getMessengerId() && data.to_id == auth_id) {
        data.typing == true
            ? messagesContainer.find(".typing-indicator").show()
            : messagesContainer.find(".typing-indicator").hide();
    }
    // scroll to bottom
    scrollToBottom(messagesContainer);
});

// listen to seen event
clientListenChannel.bind("client-seen", function (data) {
    if (data.from_id == getMessengerId() && data.to_id == auth_id) {
        if (data.seen == true) {
            $(".message-time")
                .find(".fa-check")
                .before('<span class="fas fa-check-double seen"></span> ');
            $(".message-time").find(".fa-check").remove();
        }
    }
});

// listen to contact item updates event
clientListenChannel.bind("client-contactItem", function (data) {
    if (data.to == auth_id) {
        if (data.update) {
            updateContactItem(data.from);
            // También actualizar la lista de seguidores mutuos
            updateMutualFollowersList();
        } else {
            console.error("Can not update contact item!");
        }
    }
});

// listen on message delete event
clientListenChannel.bind("client-messageDelete", function (data) {
    $("body").find(`.message-card[data-id=${data.id}]`).remove();
});
// listen on delete conversation event
clientListenChannel.bind("client-deleteConversation", function (data) {
    if (data.from == getMessengerId() && data.to == auth_id) {
        $("body").find(`.messages`).html("");
        $(".messages").find(".message-hint").show();
    }
});
// -------------------------------------
// presence channel [User Active Status]

// Limpieza inicial: asegurar que no hay estados activos falsos al cargar
$(".messenger-list-item .activeStatus").remove();

var activeStatusChannel = pusher.subscribe("presence-activeStatus");

// This event will be triggered when you join the channel
activeStatusChannel.bind("pusher:subscription_succeeded", function (members) {
    // Clear all online statuses first
    $(".messenger-list-item .activeStatus").remove();
    // Loop through the members and set their status to online
    members.each(function (member) {
        if (member.id !== auth_id) {
            // Don't show online status for myself
            setActiveStatus(1, member.id);
        }
    });
});

// Joined
activeStatusChannel.bind("pusher:member_added", function (member) {
    setActiveStatus(1, member.id);
});

// Leaved
activeStatusChannel.bind("pusher:member_removed", function (member) {
    setActiveStatus(0, member.id);
});

function handleVisibilityChange() {
    if (!document.hidden) {
        makeSeen(true);
    }
}

document.addEventListener("visibilitychange", handleVisibilityChange, false);

/**
 *-------------------------------------------------------------
 * Trigger typing event
 *-------------------------------------------------------------
 */
function isTyping(status) {
    return clientSendChannel.trigger("client-typing", {
        from_id: auth_id, // Me
        to_id: getMessengerId(), // Messenger
        typing: status,
    });
}

/**
 *-------------------------------------------------------------
 * Trigger seen event
 *-------------------------------------------------------------
 */
function makeSeen(status) {
    if (document?.hidden) {
        return;
    }
    // remove unseen counter for the user from the contacts list
    $(".messenger-list-item[data-contact=" + getMessengerId() + "]")
        .find("tr>td>b")
        .remove();
    // seen
    $.ajax({
        url: url + "/makeSeen",
        method: "POST",
        data: { _token: csrfToken, id: getMessengerId() },
        dataType: "JSON",
    });
    return clientSendChannel.trigger("client-seen", {
        from_id: auth_id, // Me
        to_id: getMessengerId(), // Messenger
        seen: status,
    });
}

/**
 *-------------------------------------------------------------
 * Trigger contact item updates
 *-------------------------------------------------------------
 */
function sendContactItemUpdates(status) {
    return clientSendChannel.trigger("client-contactItem", {
        from: auth_id, // Me
        to: getMessengerId(), // Messenger
        update: status,
    });
}

/**
 *-------------------------------------------------------------
 * Trigger message delete
 *-------------------------------------------------------------
 */
function sendMessageDeleteEvent(messageId) {
    return clientSendChannel.trigger("client-messageDelete", {
        id: messageId,
    });
}
/**
 *-------------------------------------------------------------
 * Trigger delete conversation
 *-------------------------------------------------------------
 */
function sendDeleteConversationEvent() {
    return clientSendChannel.trigger("client-deleteConversation", {
        from: auth_id,
        to: getMessengerId(),
    });
}

/**
 *-------------------------------------------------------------
 * Check internet connection using pusher states
 *-------------------------------------------------------------
 */
function checkInternet(state, selector) {
    let net_errs = 0;
    const messengerTitle = $(".messenger-headTitle");
    switch (state) {
        case "connected":
            if (net_errs < 1) {
                messengerTitle.html(messengerTitleDefault);
                selector.addClass("successBG-rgba");
                selector.find("span").hide();
                selector.slideDown("fast", function () {
                    selector.find(".ic-connected").show();
                });
                setTimeout(function () {
                    $(".internet-connection").slideUp("fast");
                }, 3000);
            }
            break;
        case "connecting":
            messengerTitle.html(
                $(".ic-connecting").text() +
                    ' <span class="beta-indicator">BETA</span>'
            );
            selector.removeClass("successBG-rgba");
            selector.find("span").hide();
            selector.slideDown("fast", function () {
                selector.find(".ic-connecting").show();
            });
            net_errs = 1;
            break;
        // Not connected
        default:
            messengerTitle.html(
                $(".ic-noInternet").text() +
                    ' <span class="beta-indicator">BETA</span>'
            );
            selector.removeClass("successBG-rgba");
            selector.find("span").hide();
            selector.slideDown("fast", function () {
                selector.find(".ic-noInternet").show();
            });
            net_errs = 1;
            break;
    }
}

/**
 *-------------------------------------------------------------
 * Get contacts
 *-------------------------------------------------------------
 */
let contactsPage = 1;
let contactsLoading = false;
let noMoreContacts = false;
function setContactsLoading(loading = false) {
    if (!loading) {
        $(".listOfContacts").find(".loading-contacts").remove();
    } else {
        $(".listOfContacts").append(
            `<div class="loading-contacts">${listItemLoading(4)}</div>`
        );
    }
    contactsLoading = loading;
}
function getContacts() {
    if (!contactsLoading && !noMoreContacts) {
        console.log("=== CALLING getContactsCustom ENDPOINT ===", {
            url: url + "/getContactsCustom",
            method: "POST",
            page: contactsPage,
        });

        setContactsLoading(true);
        $.ajax({
            url: url + "/getContactsCustom",
            method: "POST",
            data: { _token: csrfToken, page: contactsPage },
            dataType: "JSON",
            success: (data) => {
                console.log("=== getContacts SUCCESS ===", data);
                setContactsLoading(false);

                if (contactsPage < 2) {
                    // Guardar los estados activos actuales antes de reemplazar el HTML
                    const currentActiveUsers = [];
                    $(
                        ".listOfContacts .messenger-list-item .activeStatus"
                    ).each(function () {
                        const contactId = $(this)
                            .closest(".messenger-list-item")
                            .attr("data-contact");
                        if (contactId) {
                            currentActiveUsers.push(contactId);
                        }
                    });

                    $(".listOfContacts").html(data.contacts);

                    // Restaurar los estados activos después de cargar el contenido
                    currentActiveUsers.forEach((userId) => {
                        setActiveStatus(1, userId);
                    });
                } else {
                    $(".listOfContacts").append(data.contacts);
                }

                noMoreContacts = contactsPage >= data?.last_page;
                if (!noMoreContacts) contactsPage += 1;

                // Apply avatars after DOM update
                forceProfileAvatars();
                // updateSelectedContact();
            },
            error: (xhr, status, error) => {
                console.error("=== getContacts ERROR ===", {
                    xhr: xhr,
                    status: status,
                    error: error,
                    responseText: xhr.responseText,
                });
                setContactsLoading(false);
                console.error("Error fetching contacts.");
            },
        });
    }
}

/**
 *-------------------------------------------------------------
 * Update mutual followers list
 *-------------------------------------------------------------
 */
function updateMutualFollowersList() {
    $.ajax({
        url: url + "/mutualContactsList",
        method: "POST",
        data: {
            _token: csrfToken,
            current_chat_user: getMessengerId(), // No mostrar badge si estoy en esta conversación
        },
        dataType: "JSON",
        success: (data) => {
            if (data.html) {
                $(".mutual-followers-list").html(data.html);

                // NO restaurar estados automáticamente
                // Los estados online se manejarán 100% via eventos de Pusher
                // Forzar una reacción del canal de presencia para actualizar estados
                if (activeStatusChannel && activeStatusChannel.members) {
                    activeStatusChannel.members.each(function (member) {
                        if (member.id !== auth_id) {
                            setActiveStatus(1, member.id);
                        }
                    });
                }

                forceProfileAvatars();
            }
        },
        error: (error) => {
            console.error("Error updating mutual followers list:", error);
        },
    });
}

/**
 *-------------------------------------------------------------
 * Update contact item
 *-------------------------------------------------------------
 */
function updateContactItem(user_id) {
    if (user_id != auth_id) {
        $.ajax({
            url: url + "/updateContacts",
            method: "POST",
            data: {
                _token: csrfToken,
                user_id,
                current_chat_user: getMessengerId(), // Enviar el ID del usuario con quien estoy chateando
            },
            dataType: "JSON",
            success: (data) => {
                if (data.contactItem) {
                    // Verificar si el usuario está actualmente online antes de remover
                    const wasOnline =
                        $(
                            ".listOfContacts .messenger-list-item[data-contact='" +
                                user_id +
                                "'] .activeStatus"
                        ).length > 0 ||
                        $(
                            ".mutual-followers-list .messenger-list-item[data-contact='" +
                                user_id +
                                "'] .activeStatus"
                        ).length > 0;

                    // Remover CUALQUIER instancia existente del contacto
                    $(
                        ".listOfContacts .messenger-list-item[data-contact='" +
                            user_id +
                            "']"
                    ).remove();
                    $(
                        ".mutual-followers-list .messenger-list-item[data-contact='" +
                            user_id +
                            "']"
                    ).remove();

                    // Agregar al principio de la lista principal
                    $(".listOfContacts").prepend(data.contactItem);

                    // También agregar al principio de la lista de mutual followers si existe
                    if ($(".mutual-followers-list").length > 0) {
                        $(".mutual-followers-list").prepend(data.contactItem);
                    }

                    // Forzar re-aplicación de avatares
                    forceProfileAvatars();

                    // IMPORTANTE: Restaurar el estado online si el usuario estaba online
                    if (wasOnline) {
                        setActiveStatus(1, user_id);
                    }

                    // Agregar efecto visual de mensaje nuevo (solo animación de salto)
                    const updatedContact = $(
                        ".messenger-list-item[data-contact='" + user_id + "']"
                    );
                    updatedContact.addClass("new-message-highlight");

                    // Remover el efecto después de la animación (700ms)
                    setTimeout(() => {
                        updatedContact.removeClass("new-message-highlight");
                    }, 700);
                }

                if (user_id == getMessengerId()) updateSelectedContact(user_id);

                // update data-action required with [responsive design]
                cssMediaQueries();
            },
            error: (error) => {
                console.error("Error updating contact:", error);
            },
        });
    }
}

/**
 *-------------------------------------------------------------
 * Star
 *-------------------------------------------------------------
 */

function star(user_id) {
    if (getMessengerId() != auth_id) {
        $.ajax({
            url: url + "/star",
            method: "POST",
            data: { _token: csrfToken, user_id: user_id },
            dataType: "JSON",
            success: (data) => {
                data.status > 0
                    ? $(".add-to-favorite").addClass("favorite")
                    : $(".add-to-favorite").removeClass("favorite");
            },
            error: () => {
                console.error("Server error, check your response");
            },
        });
    }
}

/**
 *-------------------------------------------------------------
 * Get favorite list
 *-------------------------------------------------------------
 */
function getFavoritesList() {
    $(".messenger-favorites").html(avatarLoading(4));
    $.ajax({
        url: url + "/favorites",
        method: "POST",
        data: { _token: csrfToken },
        dataType: "JSON",
        success: (data) => {
            if (data.count > 0) {
                $(".favorites-section").show();
                $(".messenger-favorites").html(data.favorites);

                // Aplicar estados online a favoritos después de cargarlos
                if (activeStatusChannel && activeStatusChannel.members) {
                    activeStatusChannel.members.each(function (member) {
                        if (member.id !== auth_id) {
                            setActiveStatus(1, member.id);
                        }
                    });
                }
            } else {
                $(".favorites-section").hide();
            }
            // update data-action required with [responsive design]
            cssMediaQueries();
        },
        error: () => {
            console.error("Server error, check your response");
        },
    });
}

/**
 *-------------------------------------------------------------
 * Get shared photos
 *-------------------------------------------------------------
 */
function getSharedPhotos(user_id) {
    $.ajax({
        url: url + "/shared",
        method: "POST",
        data: { _token: csrfToken, user_id: user_id },
        dataType: "JSON",
        success: (data) => {
            // Cambiar texto en inglés a español
            let sharedContent = data.shared;
            if (sharedContent) {
                // Reemplazar diferentes variaciones del texto en inglés
                sharedContent = sharedContent.replace(
                    /Nothing shared yet/gi,
                    "Aún no han compartido nada"
                );
                sharedContent = sharedContent.replace(
                    /Nothing shared/gi,
                    "Aún no han compartido nada"
                );
                sharedContent = sharedContent.replace(
                    />Nothing shared yet</gi,
                    ">Aún no han compartido nada<"
                );
                sharedContent = sharedContent.replace(
                    /<span>Nothing shared yet<\/span>/gi,
                    "<span>Aún no han compartido nada</span>"
                );
            }
            $(".shared-photos-list").html(sharedContent);

            // También verificar después de insertar el HTML y cambiar directamente
            setTimeout(() => {
                $(".shared-photos-list")
                    .find(":contains('Nothing shared yet')")
                    .each(function () {
                        $(this).html(
                            $(this)
                                .html()
                                .replace(
                                    /Nothing shared yet/gi,
                                    "Aún no han compartido nada"
                                )
                        );
                    });
            }, 100);
        },
        error: () => {
            console.error("Server error, check your response");
        },
    });
}

/**
 *-------------------------------------------------------------
 * Search in messenger
 *-------------------------------------------------------------
 */
let searchPage = 1;
let noMoreDataSearch = false;
let searchLoading = false;
let searchTempVal = "";
function setSearchLoading(loading = false) {
    if (!loading) {
        $(".search-records").find(".loading-search").remove();
    } else {
        $(".search-records").append(
            `<div class="loading-search">${listItemLoading(4)}</div>`
        );
    }
    searchLoading = loading;
}
function messengerSearch(input) {
    if (input != searchTempVal) {
        searchPage = 1;
        noMoreDataSearch = false;
        searchLoading = false;
    }
    searchTempVal = input;
    if (!searchLoading && !noMoreDataSearch) {
        if (searchPage < 2) {
            $(".search-records").html("");
        }
        setSearchLoading(true);
        $.ajax({
            url: url + "/search",
            method: "GET",
            data: { _token: csrfToken, input: input, page: searchPage },
            dataType: "JSON",
            success: (data) => {
                setSearchLoading(false);
                if (searchPage < 2) {
                    $(".search-records").html(data.records);
                } else {
                    $(".search-records").append(data.records);
                }
                // update data-action required with [responsive design]
                cssMediaQueries();
                // Pagination lock & messages page
                noMoreDataSearch = searchPage >= data?.last_page;
                if (!noMoreDataSearch) searchPage += 1;
            },
            error: (error) => {
                setSearchLoading(false);
                console.error(error);
            },
        });
    }
}

/**
 *-------------------------------------------------------------
 * Delete Conversation
 *-------------------------------------------------------------
 */
function deleteConversation(id) {
    $.ajax({
        url: url + "/deleteConversation",
        method: "POST",
        data: { _token: csrfToken, id: id },
        dataType: "JSON",
        beforeSend: () => {
            // hide delete modal
            app_modal({
                show: false,
                name: "delete",
            });
            // Show waiting alert modal
            app_modal({
                show: true,
                name: "alert",
                buttons: false,
                body: loadingSVG("32px", null, "margin:auto"),
            });
        },
        success: (data) => {
            // delete contact from the list
            $(".listOfContacts")
                .find(".messenger-list-item[data-contact=" + id + "]")
                .remove();
            // refresh info
            IDinfo(id);

            if (!data.deleted)
                return alert("Error occurred, messages can not be deleted!");

            // Hide waiting alert modal
            app_modal({
                show: false,
                name: "alert",
                buttons: true,
                body: "",
            });

            sendDeleteConversationEvent();

            // update contact list item
            sendContactItemUpdates(true);
        },
        error: () => {
            console.error("Server error, check your response");
        },
    });
}

/**
 *-------------------------------------------------------------
 * Delete Message By ID
 *-------------------------------------------------------------
 */
function deleteMessage(id) {
    $.ajax({
        url: url + "/deleteMessage",
        method: "POST",
        data: { _token: csrfToken, id: id },
        dataType: "JSON",
        beforeSend: () => {
            // hide delete modal
            app_modal({
                show: false,
                name: "delete",
            });
            // Show waiting alert modal
            app_modal({
                show: true,
                name: "alert",
                buttons: false,
                body: loadingSVG("32px", null, "margin:auto"),
            });
        },
        success: (data) => {
            $(".messages").find(`.message-card[data-id=${id}]`).remove();
            if (!data.deleted)
                console.error("Error occurred, message can not be deleted!");

            sendMessageDeleteEvent(id);

            // Hide waiting alert modal
            app_modal({
                show: false,
                name: "alert",
                buttons: true,
                body: "",
            });
        },
        error: () => {
            console.error("Server error, check your response");
        },
    });
}

/**
 *-------------------------------------------------------------
 * Update Settings
 *-------------------------------------------------------------
 */
function updateSettings() {
    const formData = new FormData($("#update-settings")[0]);
    if (messengerColor) {
        formData.append("messengerColor", messengerColor);
    }
    if (dark_mode) {
        formData.append("dark_mode", dark_mode);
    }
    $.ajax({
        url: url + "/updateSettings",
        method: "POST",
        data: formData,
        dataType: "JSON",
        processData: false,
        contentType: false,
        beforeSend: () => {
            // close settings modal
            app_modal({
                show: false,
                name: "settings",
            });
            // Show waiting alert modal
            app_modal({
                show: true,
                name: "alert",
                buttons: false,
                body: loadingSVG("32px", null, "margin:auto"),
            });
        },
        success: (data) => {
            if (data.error) {
                // Show error message in alert modal
                app_modal({
                    show: true,
                    name: "alert",
                    buttons: true,
                    body: data.msg,
                });
            } else {
                // Hide alert modal
                app_modal({
                    show: false,
                    name: "alert",
                    buttons: true,
                    body: "",
                });

                // reload the page
                location.reload(true);
            }
        },
        error: () => {
            console.error("Server error, check your response");
        },
    });
}

/**
 *-------------------------------------------------------------
 * Set Active status
 *-------------------------------------------------------------
 */
function setActiveStatus(status, user_id) {
    if (user_id && user_id != auth_id) {
        // Función helper para aplicar/remover el estado activo en un elemento
        const toggleStatusForSelector = (selector) => {
            const element = $(selector);
            if (element.length > 0) {
                if (status > 0) {
                    // Remover cualquier estado activo existente
                    element.find(".activeStatus").remove();
                    // Agregar nuevo estado activo
                    element.find(".avatar").before(activeStatusCircle());
                } else {
                    // Remover estado activo
                    element.find(".activeStatus").remove();
                }
            }
        };

        // Función especial para favoritos (estructura diferente)
        const toggleStatusForFavorites = (selector) => {
            const element = $(selector);
            if (element.length > 0) {
                const container = element.parent(); // El div contenedor
                if (status > 0) {
                    // Remover cualquier estado activo existente
                    container.find(".activeStatus").remove();
                    // Para favoritos, agregar el círculo al contenedor
                    container.append(activeStatusCircle());
                } else {
                    // Remover estado activo
                    container.find(".activeStatus").remove();
                }
            }
        };

        // Aplicar estado en todas las listas: principal, seguidores mutuos y favoritos
        const mainListSelector = `.listOfContacts .messenger-list-item[data-contact="${user_id}"]`;
        const mutualListSelector = `.mutual-followers-list .messenger-list-item[data-contact="${user_id}"]`;
        const favoritesSelector = `.favorite-list-item .avatar[data-id="${user_id}"]`;

        toggleStatusForSelector(mainListSelector);
        toggleStatusForSelector(mutualListSelector);
        toggleStatusForFavorites(favoritesSelector);
    }

    // This part is for the authenticated user's status, which is handled by the backend
    // We just need to inform the backend that the user is online/offline
    if (!user_id) {
        $.ajax({
            url: url + "/setActiveStatus",
            method: "POST",
            data: { _token: csrfToken, status: status },
            dataType: "JSON",
            success: (data) => {
                // Nothing to do
            },
            error: () => {
                console.error("Server error, check your response");
            },
        });
    }
}
/**
 *-------------------------------------------------------------
 * On DOM ready
 *-------------------------------------------------------------
 */
$(document).ready(function () {
    // get contacts list
    getContacts();

    // get contacts list
    getFavoritesList();

    // Hide user details buttons on page load (no user selected initially)
    $(".messenger-infoView-btns .delete-conversation").hide();
    $(".messenger-infoView-btns .view-profile-btn").removeClass("show");
    $(".messenger-infoView-shared").hide();

    // Clear typing timeout
    clearTimeout(typingTimeout);

    // NProgress configurations
    NProgress.configure({ showSpinner: false, minimum: 0.7, speed: 500 });

    // make message input autosize.
    autosize($(".m-send"));

    // check if pusher has access to the channel [Internet status]
    pusher.connection.bind("state_change", function (states) {
        let selector = $(".internet-connection");
        checkInternet(states.current, selector);
        // listening for pusher:subscription_succeeded
        channel.bind("pusher:subscription_succeeded", function () {
            // On connection state change [Updating] and get [info & msgs]
            if (getMessengerId() != 0) {
                if (
                    $(".messenger-list-item")
                        .find("tr[data-action]")
                        .attr("data-action") == "1"
                ) {
                    $(".messenger-listView").hide();
                }
                IDinfo(getMessengerId());
            }
        });
    });

    // tabs on click, show/hide...
    $(".messenger-listView-tabs a").on("click", function () {
        var dataView = $(this).attr("data-view");
        $(".messenger-listView-tabs a").removeClass("active-tab");
        $(this).addClass("active-tab");
        $(".messenger-tab").hide();
        $(".messenger-tab[data-view=" + dataView + "]").show();
    });

    // set item active on click AND handle chat opening
    $("body").on("click", ".messenger-list-item", function () {
        $(".messenger-list-item").removeClass("m-list-active");
        $(this).addClass("m-list-active");
        const userID = $(this).attr("data-contact");
        routerPush(document.title, `${url}/${userID}`);
        updateSelectedContact(userID);

        // Only hide list view on mobile devices (small screens)
        if (window.matchMedia("(max-width: 980px)").matches) {
            $(".messenger-listView").hide();
        }

        const dataId = $(this).find("p[data-id]").attr("data-id");
        setMessengerId(dataId);
        IDinfo(dataId);

        // Mark as seen after a short delay when user actively clicks on conversation
        setTimeout(() => {
            if (getMessengerId() == dataId) {
                makeSeen(true);
            }
        }, 1000); // 1 second delay to ensure user is viewing the conversation
    });

    // show info side button
    $(".messenger-infoView nav a , .show-infoSide").on("click", function () {
        $(".messenger-infoView").toggle();
    });

    // make favorites card dragable on click to slide.
    hScroller(".messenger-favorites");

    // click action for favorite button
    $("body").on("click", ".favorite-list-item", function () {
        // Only hide list view on mobile devices (small screens)
        if (window.matchMedia("(max-width: 980px)").matches) {
            $(".messenger-listView").hide();
        }
        const uid = $(this).find("div.avatar").attr("data-id");
        setMessengerId(uid);
        IDinfo(uid);
        updateSelectedContact(uid);
        routerPush(document.title, `${url}/${uid}`);

        // Mark as seen after a short delay when user actively clicks on conversation
        setTimeout(() => {
            if (getMessengerId() == uid) {
                makeSeen(true);
            }
        }, 1000); // 1 second delay to ensure user is viewing the conversation
    });

    // list view buttons
    $(".listView-x").on("click", function () {
        $(".messenger-listView").hide();
    });
    $(".show-listView").on("click", function () {
        routerPush(document.title, `${url}/`);
        $(".messenger-listView").show();
        // Hide user details buttons when going back to list view
        $(".messenger-infoView-btns .delete-conversation").hide();
        $(".messenger-infoView-btns .view-profile-btn").removeClass("show");
        $(".messenger-infoView-shared").hide();
    });

    // click action for [add to favorite] button.
    $(".add-to-favorite").on("click", function () {
        star(getMessengerId());
    });

    // calling Css Media Queries
    cssMediaQueries();

    // message form on submit.
    $("#message-form").on("submit", (e) => {
        e.preventDefault();
        sendMessage();
    });

    // message input on keyup [Enter to send, Enter+Shift for new line]
    $("#message-form .m-send").on("keyup", (e) => {
        // if enter key pressed.
        if (e.which == 13 || e.keyCode == 13) {
            // if shift + enter key pressed, do nothing (new line).
            // if only enter key pressed, send message.
            if (!e.shiftKey) {
                triggered = isTyping(false);
                sendMessage();
            }
        }
    });

    // On [upload attachment] input change, show a preview of the image/file.
    $("body").on("change", ".upload-attachment", (e) => {
        let file = e.target.files[0];
        if (!attachmentValidate(file)) return false;
        let reader = new FileReader();
        let sendCard = $(".messenger-sendCard");
        reader.readAsDataURL(file);
        reader.addEventListener("loadstart", (e) => {
            $("#message-form").before(loadingSVG());
        });
        reader.addEventListener("load", (e) => {
            $(".messenger-sendCard").find(".loadingSVG").remove();
            if (!file.type.match("image.*")) {
                // if the file not image
                sendCard.find(".attachment-preview").remove(); // older one
                sendCard.prepend(attachmentTemplate("file", file.name));
            } else {
                // if the file is an image
                sendCard.find(".attachment-preview").remove(); // older one
                sendCard.prepend(
                    attachmentTemplate("image", file.name, e.target.result)
                );
            }
        });
    });

    function attachmentValidate(file) {
        const fileElement = $(".upload-attachment");
        const { name: fileName, size: fileSize } = file;
        const fileExtension = fileName.split(".").pop();
        if (
            !chatify.allAllowedExtensions.includes(
                fileExtension.toString().toLowerCase()
            )
        ) {
            alert("file type not allowed");
            fileElement.val("");
            return false;
        }
        // Validate file size.
        if (fileSize > chatify.maxUploadSize) {
            alert("File is too large!");
            return false;
        }
        return true;
    }

    // Attachment preview cancel button.
    $("body").on("click", ".attachment-preview .cancel", () => {
        cancelAttachment();
    });

    // typing indicator on [input] keyDown
    $("#message-form .m-send").on("keydown", () => {
        if (typingNow < 1) {
            isTyping(true);
            typingNow = 1;
        }
        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(function () {
            isTyping(false);
            typingNow = 0;
        }, 1000);
    });

    // Image modal
    $("body").on("click", ".chat-image", function () {
        let src = $(this).css("background-image").split(/"/)[1];
        $("#imageModalBox").show();
        $("#imageModalBoxSrc").attr("src", src);
    });
    $(".imageModal-close").on("click", function () {
        $("#imageModalBox").hide();
    });

    // Search input on focus
    $(".messenger-search").on("focus", function () {
        $(".messenger-tab").hide();
        $('.messenger-tab[data-view="search"]').show();
    });
    $(".messenger-search").on("blur", function () {
        setTimeout(function () {
            $(".messenger-tab").hide();
            $('.messenger-tab[data-view="users"]').show();
        }, 200);
    });
    // Search action on keyup
    const debouncedSearch = debounce(function () {
        const value = $(".messenger-search").val();
        messengerSearch(value);
    }, 500);
    $(".messenger-search").on("keyup", function (e) {
        const value = $(this).val();
        if ($.trim(value).length > 0) {
            $(".messenger-search").trigger("focus");
            debouncedSearch();
        } else {
            $(".messenger-tab").hide();
            $('.messenger-listView-tabs a[data-view="users"]').trigger("click");
        }
    });

    // Delete Conversation button
    $(".messenger-infoView-btns .delete-conversation").on("click", function () {
        app_modal({
            name: "delete",
        });
    });
    // Delete Message Button
    $("body").on("click", ".message-card .actions .delete-btn", function () {
        app_modal({
            name: "delete",
            data: $(this).data("id"),
        });
    });
    // Delete modal [on delete button click]
    $(".app-modal[data-name=delete]")
        .find(".app-modal-footer .delete")
        .on("click", function () {
            const id = $("body")
                .find(".app-modal[data-name=delete]")
                .find(".app-modal-card")
                .attr("data-modal");
            if (id == 0) {
                deleteConversation(getMessengerId());
            } else {
                deleteMessage(id);
            }
            app_modal({
                show: false,
                name: "delete",
            });
        });
    // delete modal [cancel button]
    $(".app-modal[data-name=delete]")
        .find(".app-modal-footer .cancel")
        .on("click", function () {
            app_modal({
                show: false,
                name: "delete",
            });
        });

    // Settings button action to show settings modal
    $("body").on("click", ".settings-btn", function (e) {
        e.preventDefault();
        app_modal({
            show: true,
            name: "settings",
        });
    });

    // on submit settings' form
    $("#update-settings").on("submit", (e) => {
        e.preventDefault();
        updateSettings();
    });
    // Settings modal [cancel button]
    $(".app-modal[data-name=settings]")
        .find(".app-modal-footer .cancel")
        .on("click", function () {
            app_modal({
                show: false,
                name: "settings",
            });
        });
    // change messenger color button
    $("body").on("click", ".update-messengerColor .color-btn", function () {
        messengerColor = $(this).attr("data-color");
        $(".update-messengerColor .color-btn").removeClass("m-color-active");
        $(this).addClass("m-color-active");
    });
    // Switch to Dark/Light mode
    $("body").on("click", ".dark-mode-switch", function () {
        if ($(this).attr("data-mode") == "0") {
            $(this).attr("data-mode", "1");
            $(this).removeClass("far");
            $(this).addClass("fas");
            dark_mode = "dark";
        } else {
            $(this).attr("data-mode", "0");
            $(this).removeClass("fas");
            $(this).addClass("far");
            dark_mode = "light";
        }
    });

    //Messages pagination
    actionOnScroll(
        ".m-body.messages-container",
        function () {
            fetchMessages(getMessengerId());
        },
        true
    );

    // Mark messages as seen when user scrolls to bottom
    actionOnScroll(
        ".m-body.messages-container",
        function () {
            // Only mark as seen when user scrolls to bottom (viewing messages)
            if (getMessengerId()) {
                makeSeen(true);
            }
        },
        false // false = scroll to bottom detection
    );

    //Contacts pagination
    actionOnScroll(".messenger-tab.users-tab", function () {
        getContacts();
    });
    //Search pagination
    actionOnScroll(".messenger-tab.search-tab", function () {
        messengerSearch($(".messenger-search").val());
    });
});

/**
 *-------------------------------------------------------------
 * Observer on DOM changes
 *-------------------------------------------------------------
 */
let previousMessengerId = getMessengerId();
const observer = new MutationObserver(function (mutations) {
    if (getMessengerId() !== previousMessengerId) {
        previousMessengerId = getMessengerId();
        initClientChannel();
    }
});
const config = { subtree: true, childList: true };

// start listening to changes
observer.observe(document, config);

// stop listening to changes
// observer.disconnect();

/**
 *-------------------------------------------------------------
 * Resize messaging area when resize the viewport.
 * on mobile devices when the keyboard is shown, the viewport
 * height is changed, so we need to resize the messaging area
 * to fit the new height.
 *-------------------------------------------------------------
 */
var resizeTimeout;
window.visualViewport.addEventListener("resize", (e) => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function () {
        const h = e.target.height;
        if (h) {
            $(".messenger-messagingView").css({ height: h + "px" });
        }
    }, 100);
});

/**
 *-------------------------------------------------------------
 * Emoji Picker
 *-------------------------------------------------------------
 */
const emojiButton = document.querySelector(".emoji-button");

const emojiPicker = new EmojiButton({
    theme: messengerTheme,
    autoHide: false,
    position: "top-start",
});

emojiButton.addEventListener("click", (e) => {
    e.preventDefault();
    emojiPicker.togglePicker(emojiButton);
});

emojiPicker.on("emoji", (emoji) => {
    const el = messageInput[0];
    const startPos = el.selectionStart;
    const endPos = el.selectionEnd;
    const value = messageInput.val();
    const newValue =
        value.substring(0, startPos) +
        emoji +
        value.substring(endPos, value.length);
    messageInput.val(newValue);
    el.selectionStart = el.selectionEnd = startPos + emoji.length;
    el.focus();
});

/**
 *-------------------------------------------------------------
 * Notification sounds
 *-------------------------------------------------------------
 */
function playNotificationSound(soundName, condition = false) {
    if ((document.hidden || condition) && chatify.sounds.enabled) {
        const sound = new Audio(
            `/${chatify.sounds.public_path}/${chatify.sounds[soundName]}`
        );
        sound.play();
    }
}
/**
 *-------------------------------------------------------------
 * Update and format dates to time ago.
 *-------------------------------------------------------------
 */
function updateElementsDateToTimeAgo() {
    $(".message-time").each(function () {
        const time = $(this).attr("data-time");
        $(this).find(".time").text(dateStringToTimeAgo(time));
    });
    $(".contact-item-time").each(function () {
        const time = $(this).attr("data-time");
        $(this).text(dateStringToTimeAgo(time));
    });
}
setInterval(() => {
    updateElementsDateToTimeAgo();
}, 60000);
