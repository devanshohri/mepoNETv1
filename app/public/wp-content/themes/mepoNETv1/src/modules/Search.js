import $ from 'jquery';

class Search {
    constructor() {
        this.addSearchHTML();
        this.resultsDiv = $("#search-overlay-results");
        this.openSearch = $(".header-search");
        this.closeSearch = $(".search-overlay-close");
        this.searchOverlay = $(".search-overlay");
        this.searchField = $("#search-term");
        this.typingTimer;
        this.isSpinnerVisible = false;
        this.previousValue;
        this.events();
    }


    //events

    events() {
        this.openSearch.on("click", this.openOverlay.bind(this));
        this.closeSearch.on("click", this.closeOverlay.bind(this));

        this.searchField.on("keyup",this.typingLogic.bind(this));
    }


    //methods

    typingLogic() {

        if(this.searchField.val() != this.previousValue) {
            clearTimeout(this.typingTimer);

            if(this.searchField.val()){
                if (!this.isSpinnerVisible) {
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this) , 750);
            } else {
                this.resultsDiv.html('');
                this.isSpinnerVisible = false;
            }
        }

        this.previousValue = this.searchField.val();
    }

    getResults () {

        $.getJSON(mepoNETdata.root_url + "/wp-json/mepoNETWORK/v1/search?term=" + this.searchField.val(), results => {
            this.resultsDiv.html(`
                <div class="search-overlay-results-container">
                    <div class="one-third">
                        <h2 class="search-overly-title">Posts</h2>
                        ${results.posts.length ? '<ul class="search-list">' : "<p>No posts match for your search</p>"}
                            ${results.posts.map(item => `<li><a href="${item.permalink}"><h4>${item.title}</h4></a><p>${item.postType == "post" ? `by ${item.authorName}` : ""}</p></li>`).join('')} 
                        ${results.posts.length ? '</ul>' : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overly-title">Events</h2>
                        ${results.events.length ? '<ul class="search-list">' : "<p>No Events match for your search</p>"}
                            ${results.events.map(item => `<li><a href="${item.permalink}"><h4>${item.title}</h4></a></li>`).join('')} 
                        ${results.events.length ? '</ul>' : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overly-title">Projects</h2>
                        ${results.projects.length ? '<ul class="search-list">' : "<p>No Projects match for your search</p>"}
                            ${results.projects.map(item => `<li><a href="${item.permalink}"><h4>${item.title}</h4></a></li>`).join('')} 
                        ${results.projects.length ? '</ul>' : ''}
                    </div>
                
                    <div class="one-third">
                        <h2 class="search-overly-title">People</h2>
                        ${results.users.length ? '<ul class="search-list">' : "<p>No People for your search</p>"}
                            ${results.users.map(item => `<li><a href="${item.permalink}"><h4>${item.display_name}</h4></a></li>`).join('')}
                        ${results.users.length ? '</ul>' : ''}
                    </div>
                </div>
            `);
            this.isSpinnerVisible = false;
        });
        
    }

    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll");
        this.searchField.val('');
        this.resultsDiv.html('') ;
        setTimeout(() => this.searchField.focus(), 301);
    }

    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
    }


    addSearchHTML() {
        $("body").append(`
        <div class="search-overlay">
            <div class="search-overlay-top">
                <div class="search-container">
                    <input type="text" class="search-term" placeholder="Search Everything" id="search-term">
                    <div class="material-icons search-overlay-close icon-button">close</div>
                </div>
            </div>
            <div class="search-overlay-results-container">
                <div id="search-overlay-results">
                    
                </div>
            </div>
        </div>
        `);
    }

}

export default Search