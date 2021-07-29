import React, { useEffect, useState } from "react";
import { makeStyles } from "@material-ui/core/styles";
import Grid from "@material-ui/core/Grid";
import ArrowForwardIosIcon from "@material-ui/icons/ArrowForwardIos";
import ArrowBackIosIcon from "@material-ui/icons/ArrowBackIos";
import { Button } from "@material-ui/core";

const useStyles = makeStyles({
	container: { width: "100%" },
	wrapper: { width: "100%", position: "relative" },
	contentWrapper: { overflow: "hidden", width: "100%", height: "100%" },
	content: {
		transition: "all 250ms linear",
		display: "flex",
	},
	arrows: {
		position: "absolute",
		zIndex: 1,
		top: "55%",
		transform: "translateY(-200%)",
		width: "48px",
		height: "48px",
		borderRadius: "24px",
	},
	leftArrow: {
		left: "-50px",
	},
	rightArrow: {
		right: "-50px",
	},
});

const Carousel = (props) => {
	const { children, infiniteLoop } = props;
	const [touchPosition, setTouchPosition] = useState(null);
	const [length, setLength] = useState(children.length);
	const [currentIndex, setCurrentIndex] = useState(infiniteLoop ? 4 : 0);
	const [isRepeating, setIsRepeating] = useState(
		infiniteLoop && children.length > 4
	);
	const [transitionEnabled, setTransitionEnabled] = useState(true);

	useEffect(() => {
		setLength(children.length);
		setIsRepeating(infiniteLoop && children.length > 4);
	}, [children, infiniteLoop]);

	useEffect(() => {
		if (isRepeating) {
			if (currentIndex === 4 || currentIndex === length) {
				setTransitionEnabled(true);
			}
		}
	}, [currentIndex, isRepeating, length]);

	const next = () => {
		if (isRepeating || currentIndex < length - 4) {
			setCurrentIndex((prevState) => prevState + 1);
		}
	};

	const prev = () => {
		if (isRepeating || currentIndex > 0) {
			setCurrentIndex((prevState) => prevState - 1);
		}
	};

	const handleTouchStart = (e) => {
		const touchDown = e.touches[0].clientX;
		setTouchPosition(touchDown);
	};

	const handleTouchMove = (e) => {
		const touchDown = touchPosition;

		if (touchDown === null) {
			return;
		}

		const currentTouch = e.touches[0].clientX;
		const diff = touchDown - currentTouch;

		if (diff > 5) {
			next();
		}

		if (diff < -5) {
			prev();
		}

		setTouchPosition(null);
	};

	const handleTransitionEnd = () => {
		if (isRepeating) {
			if (currentIndex === 0) {
				setTransitionEnabled(false);
				setCurrentIndex(length);
			} else if (currentIndex === length + 4) {
				setTransitionEnabled(false);
				setCurrentIndex(4);
			}
		}
	};

	const renderExtraPrev = () => {
		let output = [];
		for (let index = 0; index < 4; index++) {
			output.push(children[length - 1 - index]);
		}
		output.reverse();
		return output;
	};

	const renderExtraNext = () => {
		let output = [];
		for (let index = 0; index < 4; index++) {
			output.push(children[index]);
		}
		return output;
	};

	const classes = useStyles();

	return (
		<Grid container direction="column" className={classes.container}>
			<Grid container className={classes.wrapper}>
				{(isRepeating || currentIndex > 0) && (
					<Button
						onClick={prev}
						className={`${classes.arrows} ${classes.leftArrow}`}
					>
						<ArrowBackIosIcon />
					</Button>
				)}

				<Grid
					className={classes.contentWrapper}
					onTouchStart={handleTouchStart}
					onTouchMove={handleTouchMove}
				>
					<div
						className={classes.content}
						style={{
							transform: `translateX(-${currentIndex * (100 / 4)}%)`,
							transition: !transitionEnabled ? "none" : undefined,
						}}
						onTransitionEnd={() => handleTransitionEnd()}
					>
						{length > 4 && isRepeating && renderExtraPrev()}
						{children}
						{length > 4 && isRepeating && renderExtraNext()}
					</div>
				</Grid>
				{(isRepeating || currentIndex < length - 4) && (
					<Button
						onClick={next}
						className={`${classes.arrows} ${classes.rightArrow}`}
					>
						<ArrowForwardIosIcon />
					</Button>
				)}
			</Grid>
		</Grid>
	);
};

export default Carousel;
