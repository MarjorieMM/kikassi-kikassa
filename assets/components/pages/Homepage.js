import React from "react";
import Featured from "../partials/Featured";
import Connexion from "../partials/Connexion";
import Box from "@material-ui/core/Box";
import Grid from "@material-ui/core/Grid";
import Container from "@material-ui/core/Container";
import ListSubheader from "@material-ui/core/ListSubheader";
import { makeStyles } from "@material-ui/core/styles";

const useStyles = makeStyles((theme) => ({
	margin: {
		margin: "auto",
	},
}));

export default function Homepage() {
	const classes = useStyles();
	return (
		<Box mt={15}>
			<Container>
				<h1>Bienvenue</h1>
				{/* <ListSubheader>Subheader</ListSubheader> */}
				<Grid container>
					<Grid item xs={12} md={6}>
						<Box m={1}>
							<Connexion />
						</Box>
					</Grid>
					<Grid item xs={12} md={6}>
						<Box m={1}>
							<Connexion />
						</Box>
					</Grid>
				</Grid>
				<Grid item xs={8} className={classes.margin}>
					<Featured />
				</Grid>
			</Container>
		</Box>
	);
}
