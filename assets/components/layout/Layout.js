import React from "react";
import Footer from "./Footer";
import Navbar from "./Navbar";
import { makeStyles } from "@material-ui/core/styles";

const useStyles = makeStyles((theme) => ({
	content: {
		minHeight: "calc(100vh - 276px)",
	},
}));

function Layout({ children }) {
	const classes = useStyles();
	return (
		<>
			<Navbar />
			<main className={classes.content}>{children}</main>
			<Footer />
		</>
	);
}

export default Layout;
