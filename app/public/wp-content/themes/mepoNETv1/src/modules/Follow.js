import $ from 'jquery';


class Follow {

    constructor() {
        this.events();
    }

    events() {
        $(".author-follow-button").on("click", this.ourClickDispatcher.bind(this));
    }

    //methods
    ourClickDispatcher(e) {
        var currentFollowButton = $(e.target).closest(".author-follow-button");
        var currentFollowCount = $(".follow-count");

        if(currentFollowButton.attr('data-exists') == 'yes') {
            this.deleteFollow(currentFollowButton, currentFollowCount);
        } else{
            this.createFollow(currentFollowButton, currentFollowCount);
        }
    }

    createFollow(currentFollowButton, currentFollowCount) {
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', mepoNETdata.nonce);
            },
            url: mepoNETdata.root_url + '/wp-json/mepoNET/v1/manageFollow',
            type: 'POST',
            data: {'userID': currentFollowButton.data('user')},
            success: (response) => {
                currentFollowButton.attr('data-exists', 'yes');
                currentFollowButton.text('Unfollow');
                var followCount = parseInt(currentFollowCount.text().trim(), 10);
                followCount++;
                currentFollowCount.text(followCount);
                currentFollowButton.attr("data-follow", response);
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            },
        });
    }

    deleteFollow(currentFollowButton, currentFollowCount) {
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', mepoNETdata.nonce);
            },
            url: mepoNETdata.root_url + '/wp-json/mepoNET/v1/manageFollow',
            data: {'follow': currentFollowButton.attr('data-follow')},
            type: "DELETE",
            success: (response) => {
                currentFollowButton.attr('data-exists', 'no');
                currentFollowButton.text('Follow');
                var followCount = parseInt(currentFollowCount.text().trim(), 10);
                followCount--;
                currentFollowCount.text(followCount);
                currentFollowButton.attr("data-follow", '')
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            },
        });
    }
}

export default Follow;