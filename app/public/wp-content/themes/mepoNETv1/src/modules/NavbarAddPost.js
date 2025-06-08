import $ from 'jquery';

class NavbarAddPost {
    constructor() {
        this.openAddPost = $(".header-add");
        this.closeAddPost = $("#header-add-close");
        this.addPostOverlay = $(".header-add-overlay");
        this.headerButtons = $(".header-buttons");
        this.addButtons = $(".header-add-buttons div a"); // Select the add buttons
        this.events();
    }


events() {
    this.openAddPost.on("click", this.openAddPostOverlay.bind(this));
    this.closeAddPost.on("click", this.closeAddPostOverlay.bind(this));
}

openAddPostOverlay() {
    this.addPostOverlay.addClass("header-add-overlay--active");
    this.headerButtons.addClass("header-buttons--hidden");
}

closeAddPostOverlay() {
    this.addPostOverlay.removeClass("header-add-overlay--active");
    this.headerButtons.removeClass("header-buttons--hidden");
}

}

export default  NavbarAddPost;