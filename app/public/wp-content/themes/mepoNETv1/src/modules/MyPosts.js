import $ from 'jquery';

class MyPosts {

    constructor() {
        this.events();
    }

    events() {
        $(".delete-post-bttn").on("click", this.deletePost);
        $(".edit-post-bttn").on("click", this.editPost);
    }

    //Methods

    // Edit Post Method
    editPost(e) {
        var postId = $(e.target).attr('data-post-id'); // Get the post ID from the button's data attribute

        // Redirect to the edit post page with the post ID as a query parameter
        window.location.href = '/edit-post/?post_id=' + postId; // Change the URL to your edit post page
    }

    deletePost(e){
        var thisPost = $(e.target).parents(".author-post");
        var postType = thisPost.attr('data-post-type');

        var postSlug = '';
        if (postType === 'post') {
            postSlug = 'posts';
        } else if (postType === 'event') {
            postSlug = 'event';
        } else if (postType === 'project') {
            postSlug = 'project';
        }
        
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', mepoNETdata.nonce)
            },
            url: mepoNETdata.root_url + '/wp-json/wp/v2/' + postSlug  + '/' + thisPost.attr("data-id"),
            type: 'DELETE',
            success: (response) => {
                thisPost.fadeOut();
                console.log('deletePostSuccess');
                console.log(response);
            },
            error: (response) => {
                console.log('deletePostError');
                console.log(response);
            }
        });
    }
}

export default MyPosts;