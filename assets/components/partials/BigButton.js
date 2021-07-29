import React from "react";
import { Link } from "react-router-dom";
import { makeStyles, styled } from "@material-ui/core/styles";
import { spacing } from "@material-ui/system";
import MuiButton from "@material-ui/core/Button";

const useStyles = makeStyles((theme) => ({
	shadowButton: {
		backgroundColor: theme.palette.primary.main,
		flexBasis: "100%",
		boxShadow:
			"rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset",
		"&:hover": {
			backgroundColor: theme.palette.primary.light,
			color: theme.palette.primary.dark,
		},
	},

	linkText: {
		color: "#fff",
		fontWeight: "bold",
	},
	borderRadius: {
		borderRadius: "30px",
	},
}));

const Button = styled(MuiButton)(spacing);

function BigButton({ link, titre, fonctionnement }) {
	const classes = useStyles();
	const radius = fonctionnement ? classes.borderRadius : "";
	return (
		<>
			<Button
				my={1}
				mx={0.5}
				p={1}
				variant="contained"
				className={` ${radius} ${classes.shadowButton}`}
			>
				<Link to={link} className={classes.linkText}>
					{titre}
				</Link>
			</Button>
		</>
	);
}

export default BigButton;
