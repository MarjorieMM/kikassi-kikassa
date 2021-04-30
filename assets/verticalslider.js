const appController = (function () {
    const key = {
        up: 38,
        down: 40
    };

    // Our total transformY value will be stored here
    const data = {
        total: {
            Ytransform: 0
        }
    };

    return {
        debounce: (func, wait) => {
            let timeout;

            return function executedFunction(...args) {
                const later = () => {
                    timeout = null;
                    func(...args);
                };

                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        // Tells which direction it has to slide to
        getDir: function (e) {
            if (e <= -1 || e.keyCode === key.up || e.wich === key.up) {
                return "down";
            } else if (
                e >= 1 ||
                e.keyCode === key.down ||
                e.wich === key.down
            ) {
                return "up";
            }
        },

        // Updates our total transformY value
        updateTotal: function () {
            return function (status, value) {
                return status === "inc"
                    ? (data.total.Ytransform += value)
                    : status === "dec"
                    ? (data.total.Ytransform -= value)
                    : data.total.Ytransform;
            };
        },

        getData: function () {
            return data;
        }
    };
})();

const UIController = (function () {
    const DOMstrings = {
        slider: ".slider",
        card: ".slider__el"
    };

    const UIData = {
        index: 0
    };

    const getElements = function () {
        return {
            cards: document.querySelectorAll(DOMstrings.card)
        };
    };

    // Will give us the exact size of our element: offset height + it's margin
    // To get our margin, I saved this value on a css variable '--card-margin'
    const getCardSize = function () {
        let height, margin;

        height = document.querySelector(DOMstrings.card).offsetHeight;
        margin = getComputedStyle(document.documentElement).getPropertyValue(
            "--card-margin"
        );
        margin = margin.replace("%", "");

        function calcFh() {
            let fullHeight = height + (height / 100) * margin;
            return fullHeight;
        }

        return calcFh();
    };

    // Gets our current active element index
    const getIndex = function () {
        const els = getElements(),
            cards = els.cards,
            cardsArr = Array.from(cards);

        let currentIndex = cardsArr.findIndex(
            (card) => card.dataset.status === "active"
        );

        UIData.index = currentIndex;
        return currentIndex;
    };

    // Updates index
    const setIndex = function () {
        let index = getIndex();

        return function (decrement) {
            decrement ? index-- : index++;

            UIData.index = index;
            return index;
        };
    };
    const keepIndex = setIndex();

    // Applies style to our active element
    const setActive = function (index) {
        const els = getElements(),
            cards = els.cards,
            cardsArr = Array.from(cards);

        cardsArr.forEach((card) => (card.dataset.status = ""));
        cardsArr[index].dataset.status = "active";
    };

    // Limiting the slider to get passed the last element
    const limit = function (index) {
        const els = getElements(),
            cards = els.cards;

        if (index > cards.length - 2) return "maxReached";
        if (index <= 0) return "minReached";
    };

    return {
        // Main sliding function
        slide: function (dir, updateValue) {
            let cardSize, yValue, index;

            const stop = limit(UIData.index);

            cardSize = getCardSize();

            if (dir === "down") {
                if (stop !== "minReached") {
                    yValue = updateValue("inc", cardSize);
                    index = keepIndex(true);
                    setActive(index);
                }
            } else if (dir === "up") {
                if (stop !== "maxReached") {
                    yValue = updateValue("dec", cardSize);
                    index = keepIndex(false);
                    setActive(index);
                }
            }

            if ((yValue > 0 && yValue < 10) || (yValue < 0 && yValue > -10))
                yValue = 0;

            document.querySelector(DOMstrings.slider).style.transform =
                "translateY(" + yValue + "px)";
        }
    };
})();

// Controls user actions
const controller = (function (UICtrl, appCtrl) {
    const eventListeners = function () {
        let debounce,
            delay,
            scrollDir,
            dir,
            arr = [];

        delay = 1200;
        debounce = appCtrl.debounce;
        scrollDir = appCtrl.getDir;

        const onScroll = debounce((e) => {
            dir = scrollDir(e);
            ctrlSlide(dir);
            arr = [];
        }, delay);

        const onKey = debounce((e) => {
            dir = scrollDir(e);
            ctrlSlide(dir);
        }, delay / 3);

        //events
        window.addEventListener("wheel", (e) => {
            let yValue;

            // fix for touchpads our magic mouse deltaY value
            if (arr.length < 4) {
                arr.push(e.deltaY);
                if (arr.length > 3) yValue = arr[2];
            }

            if (yValue !== undefined) onScroll(yValue);
        });

        document.addEventListener("keydown", (e) => {
            onKey(e);
        });
    };

    const ctrlSlide = function (dir) {
        let total;

        total = appCtrl.updateTotal();

        UICtrl.slide(dir, total);
    };

    return {
        init: function () {
            eventListeners();
        }
    };
})(UIController, appController);

controller.init();
