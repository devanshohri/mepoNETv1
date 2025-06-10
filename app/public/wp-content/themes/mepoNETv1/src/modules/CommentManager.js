import $ from 'jquery';

class CommentManager {
  constructor() {
        this.events();
    }

    events() {
        $(document).on("click", ".delete-comment-bttn", this.deleteComment.bind(this));
    }

    deleteComment(e) {
        var thisComment = $(e.target).closest(".posted-comment");
        var commentID = thisComment.data('comment-id');
        var commentCountElement = $(e.target).closest('.feed-post').find('.comment-count h3');

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', mepoNETdata.nonce);
            },
            url: mepoNETdata.root_url + '/wp-json/wp/v2/comments/' + commentID,
            type: 'DELETE',
            success: (response) => {
                thisComment.fadeOut();
                console.log('deleteCommentSuccess');
                console.log(response);

                // Update the comment count
                var commentCount = parseInt(commentCountElement.html(), 10);
                commentCount--;
                commentCountElement.html(commentCount);
            },
            error: (response) => {
                console.log('deleteCommentError');
                console.log(response);
            }
        });
    }

}

export default CommentManager;
