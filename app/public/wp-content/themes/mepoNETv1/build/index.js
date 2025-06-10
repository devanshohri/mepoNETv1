/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/modules/CommentManager.js":
/*!***************************************!*\
  !*** ./src/modules/CommentManager.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

class CommentManager {
  constructor() {
    this.events();
  }
  events() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(document).on("click", ".delete-comment-bttn", this.deleteComment.bind(this));
  }
  deleteComment(e) {
    var thisComment = jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target).closest(".posted-comment");
    var commentID = thisComment.data('comment-id');
    var commentCountElement = jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target).closest('.feed-post').find('.comment-count h3');
    jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader('X-WP-Nonce', mepoNETdata.nonce);
      },
      url: mepoNETdata.root_url + '/wp-json/wp/v2/comments/' + commentID,
      type: 'DELETE',
      success: response => {
        thisComment.fadeOut();
        console.log('deleteCommentSuccess');
        console.log(response);

        // Update the comment count
        var commentCount = parseInt(commentCountElement.html(), 10);
        commentCount--;
        commentCountElement.html(commentCount);
      },
      error: response => {
        console.log('deleteCommentError');
        console.log(response);
      }
    });
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CommentManager);

/***/ }),

/***/ "./src/modules/Follow.js":
/*!*******************************!*\
  !*** ./src/modules/Follow.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

class Follow {
  constructor() {
    this.events();
  }
  events() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(".author-follow-button").on("click", this.ourClickDispatcher.bind(this));
  }

  //methods
  ourClickDispatcher(e) {
    var currentFollowButton = jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target).closest(".author-follow-button");
    var currentFollowCount = jquery__WEBPACK_IMPORTED_MODULE_0___default()(".follow-count");
    if (currentFollowButton.attr('data-exists') == 'yes') {
      this.deleteFollow(currentFollowButton, currentFollowCount);
    } else {
      this.createFollow(currentFollowButton, currentFollowCount);
    }
  }
  createFollow(currentFollowButton, currentFollowCount) {
    jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader('X-WP-Nonce', mepoNETdata.nonce);
      },
      url: mepoNETdata.root_url + '/wp-json/mepoNET/v1/manageFollow',
      type: 'POST',
      data: {
        'userID': currentFollowButton.data('user')
      },
      success: response => {
        currentFollowButton.attr('data-exists', 'yes');
        currentFollowButton.text('Unfollow');
        var followCount = parseInt(currentFollowCount.text().trim(), 10);
        followCount++;
        currentFollowCount.text(followCount);
        currentFollowButton.attr("data-follow", response);
        console.log(response);
      },
      error: response => {
        console.log(response);
      }
    });
  }
  deleteFollow(currentFollowButton, currentFollowCount) {
    jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader('X-WP-Nonce', mepoNETdata.nonce);
      },
      url: mepoNETdata.root_url + '/wp-json/mepoNET/v1/manageFollow',
      data: {
        'follow': currentFollowButton.attr('data-follow')
      },
      type: "DELETE",
      success: response => {
        currentFollowButton.attr('data-exists', 'no');
        currentFollowButton.text('Follow');
        var followCount = parseInt(currentFollowCount.text().trim(), 10);
        followCount--;
        currentFollowCount.text(followCount);
        currentFollowButton.attr("data-follow", '');
        console.log(response);
      },
      error: response => {
        console.log(response);
      }
    });
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Follow);

/***/ }),

/***/ "./src/modules/Like.js":
/*!*****************************!*\
  !*** ./src/modules/Like.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

class Like {
  constructor() {
    this.events();
  }
  events() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(".like-bttn").on("click", this.ourClickDispatcher.bind(this));
  }

  // methods
  ourClickDispatcher(e) {
    var currentLikeBox = jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target).closest(".like-bttn");
    if (currentLikeBox.attr("data-exists") == "yes") {
      this.deleteLike(currentLikeBox);
    } else {
      this.createLike(currentLikeBox);
    }
  }
  createLike(currentLikeBox) {
    jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader("X-WP-Nonce", mepoNETdata.nonce);
      },
      url: mepoNETdata.root_url + "/wp-json/mepoNET/v1/manageLike",
      type: "POST",
      data: {
        "postId": currentLikeBox.data("post")
      },
      success: response => {
        currentLikeBox.attr("data-exists", "yes");
        currentLikeBox.find('.material-symbols-outlined').removeClass('material-symbols-outlined').addClass('material-symbols-rounded');
        var likeCountElement = currentLikeBox.find(".like-count");
        var likeCount = parseInt(likeCountElement.find("h4").html(), 10);
        likeCount++;
        likeCountElement.find("h4").html(likeCount);
        currentLikeBox.attr("data-like", response);
        console.log(response);
      },
      error: response => {
        console.log(response);
      }
    });
  }
  deleteLike(currentLikeBox) {
    jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader("X-WP-Nonce", mepoNETdata.nonce);
      },
      url: mepoNETdata.root_url + "/wp-json/mepoNET/v1/manageLike",
      data: {
        "like": currentLikeBox.attr("data-like")
      },
      type: "DELETE",
      success: response => {
        currentLikeBox.attr("data-exists", "no");
        currentLikeBox.find('.material-symbols-rounded').removeClass('material-symbols-rounded').addClass('material-symbols-outlined');
        var likeCountElement = currentLikeBox.find(".like-count");
        var likeCount = parseInt(likeCountElement.find("h4").html(), 10);
        likeCount--;
        likeCountElement.find("h4").html(likeCount);
        currentLikeBox.attr("data-like", "");
        console.log(response);
      },
      error: response => {
        console.log(response);
      }
    });
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Like);

/***/ }),

/***/ "./src/modules/MyPosts.js":
/*!********************************!*\
  !*** ./src/modules/MyPosts.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

class MyPosts {
  constructor() {
    this.events();
  }
  events() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(".delete-post-bttn").on("click", this.deletePost);
    jquery__WEBPACK_IMPORTED_MODULE_0___default()(".edit-post-bttn").on("click", this.editPost);
  }

  //Methods

  // Edit Post Method
  editPost(e) {
    var postId = jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target).attr('data-post-id'); // Get the post ID from the button's data attribute

    // Redirect to the edit post page with the post ID as a query parameter
    window.location.href = '/edit-post/?post_id=' + postId; // Change the URL to your edit post page
  }
  deletePost(e) {
    var thisPost = jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target).parents(".author-post");
    var postType = thisPost.attr('data-post-type');
    var postSlug = '';
    if (postType === 'post') {
      postSlug = 'posts';
    } else if (postType === 'event') {
      postSlug = 'event';
    } else if (postType === 'project') {
      postSlug = 'project';
    }
    jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader('X-WP-Nonce', mepoNETdata.nonce);
      },
      url: mepoNETdata.root_url + '/wp-json/wp/v2/' + postSlug + '/' + thisPost.attr("data-id"),
      type: 'DELETE',
      success: response => {
        thisPost.fadeOut();
        console.log('deletePostSuccess');
        console.log(response);
      },
      error: response => {
        console.log('deletePostError');
        console.log(response);
      }
    });
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MyPosts);

/***/ }),

/***/ "./src/modules/NavbarAddPost.js":
/*!**************************************!*\
  !*** ./src/modules/NavbarAddPost.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

class NavbarAddPost {
  constructor() {
    this.openAddPost = jquery__WEBPACK_IMPORTED_MODULE_0___default()(".header-add");
    this.closeAddPost = jquery__WEBPACK_IMPORTED_MODULE_0___default()("#header-add-close");
    this.addPostOverlay = jquery__WEBPACK_IMPORTED_MODULE_0___default()(".header-add-overlay");
    this.headerButtons = jquery__WEBPACK_IMPORTED_MODULE_0___default()(".header-buttons");
    this.addButtons = jquery__WEBPACK_IMPORTED_MODULE_0___default()(".header-add-buttons div a"); // Select the add buttons
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (NavbarAddPost);

/***/ }),

/***/ "./src/modules/NewActivity.js":
/*!************************************!*\
  !*** ./src/modules/NewActivity.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

class NewActivity {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (NewActivity);

/***/ }),

/***/ "./src/modules/Search.js":
/*!*******************************!*\
  !*** ./src/modules/Search.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

class Search {
  constructor() {
    this.addSearchHTML();
    this.resultsDiv = jquery__WEBPACK_IMPORTED_MODULE_0___default()("#search-overlay-results");
    this.openSearch = jquery__WEBPACK_IMPORTED_MODULE_0___default()(".header-search");
    this.closeSearch = jquery__WEBPACK_IMPORTED_MODULE_0___default()(".search-overlay-close");
    this.searchOverlay = jquery__WEBPACK_IMPORTED_MODULE_0___default()(".search-overlay");
    this.searchField = jquery__WEBPACK_IMPORTED_MODULE_0___default()("#search-term");
    this.typingTimer;
    this.isSpinnerVisible = false;
    this.previousValue;
    this.events();
  }

  //events

  events() {
    this.openSearch.on("click", this.openOverlay.bind(this));
    this.closeSearch.on("click", this.closeOverlay.bind(this));
    this.searchField.on("keyup", this.typingLogic.bind(this));
  }

  //methods

  typingLogic() {
    if (this.searchField.val() != this.previousValue) {
      clearTimeout(this.typingTimer);
      if (this.searchField.val()) {
        if (!this.isSpinnerVisible) {
          this.resultsDiv.html('<div class="spinner-loader"></div>');
          this.isSpinnerVisible = true;
        }
        this.typingTimer = setTimeout(this.getResults.bind(this), 750);
      } else {
        this.resultsDiv.html('');
        this.isSpinnerVisible = false;
      }
    }
    this.previousValue = this.searchField.val();
  }
  getResults() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default().getJSON(mepoNETdata.root_url + "/wp-json/mepoNETWORK/v1/search?term=" + this.searchField.val(), results => {
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
    jquery__WEBPACK_IMPORTED_MODULE_0___default()("body").addClass("body-no-scroll");
    this.searchField.val('');
    this.resultsDiv.html('');
    setTimeout(() => this.searchField.focus(), 301);
  }
  closeOverlay() {
    this.searchOverlay.removeClass("search-overlay--active");
    jquery__WEBPACK_IMPORTED_MODULE_0___default()("body").removeClass("body-no-scroll");
  }
  addSearchHTML() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()("body").append(`
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Search);

/***/ }),

/***/ "./src/modules/Window.js":
/*!*******************************!*\
  !*** ./src/modules/Window.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);

class Window {
  constructor() {
    this.openWindow = jquery__WEBPACK_IMPORTED_MODULE_0___default()(".window-open");
    this.closeWindow = jquery__WEBPACK_IMPORTED_MODULE_0___default()(".window-close");
    this.WindowOverlay = jquery__WEBPACK_IMPORTED_MODULE_0___default()(".window-overlay");
    this.events();
  }
  events() {
    this.openWindow.on("click", this.openOverlay.bind(this));
    this.closeWindow.on("click", this.closeOverlay.bind(this));
  }
  openOverlay() {
    console.log("lknlkelfkm");
    this.WindowOverlay.addClass("window-overlay--active");
    jquery__WEBPACK_IMPORTED_MODULE_0___default()("body").addClass("body-no-scroll");
  }
  closeOverlay() {
    this.WindowOverlay.removeClass("window-overlay--active");
    jquery__WEBPACK_IMPORTED_MODULE_0___default()("body").removeClass("body-no-scroll");
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Window);

/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/***/ ((module) => {

module.exports = window["jQuery"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modules_Search__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modules/Search */ "./src/modules/Search.js");
/* harmony import */ var _modules_MyPosts__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/MyPosts */ "./src/modules/MyPosts.js");
/* harmony import */ var _modules_Follow__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./modules/Follow */ "./src/modules/Follow.js");
/* harmony import */ var _modules_Window__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./modules/Window */ "./src/modules/Window.js");
/* harmony import */ var _modules_Like__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./modules/Like */ "./src/modules/Like.js");
/* harmony import */ var _modules_NavbarAddPost__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./modules/NavbarAddPost */ "./src/modules/NavbarAddPost.js");
/* harmony import */ var _modules_NewActivity__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./modules/NewActivity */ "./src/modules/NewActivity.js");
/* harmony import */ var _modules_CommentManager__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./modules/CommentManager */ "./src/modules/CommentManager.js");








const newSearch = new _modules_Search__WEBPACK_IMPORTED_MODULE_0__["default"]();
const myPosts = new _modules_MyPosts__WEBPACK_IMPORTED_MODULE_1__["default"]();
const follow = new _modules_Follow__WEBPACK_IMPORTED_MODULE_2__["default"]();
const window = new _modules_Window__WEBPACK_IMPORTED_MODULE_3__["default"]();
const like = new _modules_Like__WEBPACK_IMPORTED_MODULE_4__["default"]();
const navbarAddPost = new _modules_NavbarAddPost__WEBPACK_IMPORTED_MODULE_5__["default"]();
const newActivity = new _modules_NewActivity__WEBPACK_IMPORTED_MODULE_6__["default"]();
const commentManager = new _modules_CommentManager__WEBPACK_IMPORTED_MODULE_7__["default"]();

//Feed
})();

/******/ })()
;
//# sourceMappingURL=index.js.map