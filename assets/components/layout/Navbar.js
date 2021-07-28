import React, { useState } from "react";
import { Link } from "react-router-dom";
import logo from "../../../public/photos/logos/FOURMI LOGO5.1. vert web 1000Px.png";
import { makeStyles, styled } from "@material-ui/core/styles";
import {
	AppBar,
	Toolbar,
	Box,
	useMediaQuery,
	List,
	ListItem,
	Accordion,
	AccordionSummary,
	Divider,
} from "@material-ui/core";
import { spacing } from "@material-ui/system";
import MuiButton from "@material-ui/core/Button";
import MuiAccordionDetails from "@material-ui/core/AccordionDetails";
import MenuIcon from "@material-ui/icons/Menu";
import TitleBar from "./TitleBar";

const useStyles = makeStyles((theme) => ({
	appBar: {
		borderRadius: "5px",
	},
	margin: {
		margin: "10px 0",
	},
	shadowButton: {
		flexBasis: "100%",
		boxShadow:
			"rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset",
		"&:hover": {
			backgroundColor: theme.palette.primary.light,
			color: theme.palette.primary.dark,
		},
	},
	primary: {
		backgroundColor: "#fafafa",
	},
	darkPrimary: {
		backgroundColor: theme.palette.primary.dark,
	},
	lightPrimary: {
		backgroundColor: theme.palette.primary.main,
	},
	mainColor: {
		color: theme.palette.primary.main,
	},
	mainBgColor: {
		backgroundColor: theme.palette.primary.main,
	},

	linkText: {
		color: "#fff",
		fontWeight: "bold",
	},
	centerLinks: {
		justifyContent: "center",
	},
	toolbar: {
		display: "flex",
		justifyContent: "center",
	},
}));

const Button = styled(MuiButton)(spacing);
const AccordionDetails = styled(MuiAccordionDetails)(spacing);

function Navbar() {
	const [open, setOpen] = useState(false);
	const [visibleTitle, setVisibleTitle] = useState(true);
	const isMobile = useMediaQuery((theme) => theme.breakpoints.down("sm"));
	const classes = useStyles();

	const handleOpenAccordion = () => {
		!open ? setOpen(true) : setOpen(false);
		visibleTitle ? setVisibleTitle(false) : setVisibleTitle(true);
	};

	return (
		<AppBar className={classes.primary}>
			{isMobile ? (
				<Accordion className={classes.primary} expanded={open}>
					<AccordionSummary
						onClick={handleOpenAccordion}
						expandIcon={<MenuIcon className={classes.mainColor} />}
						aria-controls="mobile-menu"
						id="mobile-menu"
					>
						<Link to="/" onClick={(e) => e.stopPropagation()}>
							<img src={logo} alt="logo-fourmi-kikassa" width="20%" />
						</Link>
					</AccordionSummary>
					<AccordionDetails
						p={0}
						className={`${classes.centerLinks} ${classes.lightPrimary}`}
					>
						<List onClick={handleOpenAccordion}>
							<ListItem
								className={`${classes.centerLinks} ${classes.lightPrimary}`}
							>
								<Link to="/" className={classes.linkText}>
									Accueil
								</Link>
							</ListItem>
							<Divider />
							<ListItem
								className={`${classes.centerLinks} ${classes.lightPrimary}`}
							>
								<Link
									to="/bibliotheque-objets/mon-compte"
									className={classes.linkText}
								>
									Mon profil
								</Link>
							</ListItem>
							<Divider />
							<ListItem
								className={`${classes.centerLinks} ${classes.lightPrimary}`}
							>
								<Link
									to="/bibliotheque-objets/mon-compte/mon-historique"
									className={classes.linkText}
								>
									Mes emprunts
								</Link>
							</ListItem>
							<Divider />
							<ListItem
								className={`${classes.centerLinks} ${classes.lightPrimary}`}
							>
								<Link to="/bibliotheque-objets" className={classes.linkText}>
									Les objets
								</Link>
							</ListItem>
						</List>
					</AccordionDetails>
				</Accordion>
			) : (
				<Toolbar className={classes.toolbar}>
					<Link className={classes.margin} to="/">
						<img src={logo} alt="logo-fourmi-kikassa" width="12%" />
					</Link>
					<Box display="flex" flexDirection="row" justifyContent="flex-end">
						<Button
							my={1}
							mx={0.5}
							p={1}
							className={`${classes.mainBgColor} ${classes.shadowButton}`}
							variant="contained"
						>
							<Link
								to="/bibliotheque-objets/mon-compte"
								className={classes.linkText}
							>
								Mon profil
							</Link>
						</Button>
						<Button
							my={1}
							mx={0.5}
							p={1}
							variant="contained"
							className={`${classes.mainBgColor} ${classes.shadowButton}`}
						>
							<Link
								to="/bibliotheque-objets/mon-compte/mon-historique"
								className={classes.linkText}
							>
								Mes emprunts
							</Link>
						</Button>
						<Button
							my={1}
							mx={0.5}
							p={1}
							variant="contained"
							className={`${classes.mainBgColor} ${classes.shadowButton}`}
						>
							<Link
								to="/bibliotheque-objets/mon-compte/mon-historique"
								className={classes.linkText}
							>
								Mes emprunts
							</Link>
						</Button>
						<Button
							my={1}
							mx={0.5}
							p={1}
							variant="contained"
							className={`${classes.mainBgColor} ${classes.shadowButton}`}
						>
							<Link to="/bibliotheque-objets" className={classes.linkText}>
								Les objets
							</Link>
						</Button>
					</Box>
				</Toolbar>
			)}
			<TitleBar visible={visibleTitle} />
		</AppBar>
	);
}

export default Navbar;
