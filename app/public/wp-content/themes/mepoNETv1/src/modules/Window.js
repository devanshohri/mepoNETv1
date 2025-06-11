import $ from 'jquery';

class Window {
    constructor() {
        this.openWindow = $(".window-open");
        this.closeWindow = $(".window-close");
        this.WindowOverlay = $(".window-overlay");

        this.events();
    }

    events() {
        this.openWindow.on("click", this.openOverlay.bind(this));
        this.closeWindow.on("click", this.closeOverlay.bind(this));
    }

    openOverlay() {
        console.log("lknlkelfkm")
        this.WindowOverlay.addClass("window-overlay--active").removeClass("window-overlay");
        $("body").addClass("body-no-scroll");
    }

    closeOverlay() {
        this.WindowOverlay.removeClass("window-overlay--active");
        $("body").removeClass("body-no-scroll");
    }

}

export default Window;
