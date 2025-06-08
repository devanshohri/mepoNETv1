import $ from "jquery"

class Like {
  constructor() {
    this.events()
  }

  events() {
    $(".like-bttn").on("click", this.ourClickDispatcher.bind(this))
  }

  // methods
  ourClickDispatcher(e) {
    var currentLikeBox = $(e.target).closest(".like-bttn")

    if (currentLikeBox.attr("data-exists") == "yes") {
      this.deleteLike(currentLikeBox)
    } else {
      this.createLike(currentLikeBox)
    }
  }

  createLike(currentLikeBox) {
    $.ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader("X-WP-Nonce", mepoNETdata.nonce)
      },
      url: mepoNETdata.root_url + "/wp-json/mepoNET/v1/manageLike",
      type: "POST",
      data: { "postId": currentLikeBox.data("post") },
      success: (response) => {
        currentLikeBox.attr("data-exists", "yes")
        currentLikeBox.find('.material-symbols-outlined').removeClass('material-symbols-outlined').addClass('material-symbols-rounded');
        var likeCountElement = currentLikeBox.find(".like-count");
        var likeCount = parseInt(likeCountElement.find("h4").html(), 10)
        likeCount++
        likeCountElement.find("h4").html(likeCount)
        currentLikeBox.attr("data-like", response)
        console.log(response)
      },
      error: response => {
        console.log(response)
      }
    })
  }

  deleteLike(currentLikeBox) {
    $.ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader("X-WP-Nonce", mepoNETdata.nonce)
      },
      url: mepoNETdata.root_url + "/wp-json/mepoNET/v1/manageLike",
      data: { "like": currentLikeBox.attr("data-like") },
      type: "DELETE",
      success: (response) => {
        currentLikeBox.attr("data-exists", "no")
        currentLikeBox.find('.material-symbols-rounded').removeClass('material-symbols-rounded').addClass('material-symbols-outlined');
        var likeCountElement = currentLikeBox.find(".like-count");
        var likeCount = parseInt(likeCountElement.find("h4").html(), 10)
        likeCount--
        likeCountElement.find("h4").html(likeCount)
        currentLikeBox.attr("data-like", "")
        console.log(response)
      },
      error: response => {
        console.log(response)
      }
    })
  }
}

export default Like
