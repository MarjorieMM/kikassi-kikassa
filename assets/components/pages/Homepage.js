import React, { useState } from "react";
import Featured from "../partials/Featured";
import Connexion from "../partials/Connexion";
import Box from "@material-ui/core/Box";
import { useMediaQuery } from "@material-ui/core";
import Grid from "@material-ui/core/Grid";
import Container from "@material-ui/core/Container";
import { makeStyles } from "@material-ui/core/styles";
import BigButton from "../partials/BigButton";
import FeaturedMobile from "../partials/FeaturedMobile";

const useStyles = makeStyles((theme) => ({
	margin: {
		margin: "auto",
		textAlign: "center",
	},
	button: {
		backgroundColor: theme.palette.primary.main,
	},
}));

export default function Homepage() {
	const [token, setToken] = useState();
	const isMobile = useMediaQuery((theme) => theme.breakpoints.down("xs"));
	const classes = useStyles();
	return (
		<Box mt={15}>
			<Container>
				<Grid container>
					<Grid item xs={12} md={6}>
						<Box my={1} mx={6}>
							{/* <Connexion /> */}
						</Box>
					</Grid>
					<Grid item xs={12} md={6}>
						<Box my={1} mt={6} mb={8}>
							{!token ? <Connexion setToken={setToken} /> : ""}
						</Box>
					</Grid>
				</Grid>
				<Box m={5}>
					{isMobile ? (
						<Grid item xs={12} md={8} className={classes.margin}>
							<FeaturedMobile />
						</Grid>
					) : (
						<Grid item xs={12} md={8} className={classes.margin}>
							<Featured />
						</Grid>
					)}
				</Box>
				<Grid container>
					<Grid item xs={12} className={classes.margin}>
						<Box mb={5}>
							<BigButton
								fonctionnement={true}
								link="/bibliotheque-objets/comment-ca-marche"
								titre="Je découvre le fonctionnement de la Bibiliothèque d'objets"
								className={classes.button}
							/>
						</Box>
					</Grid>
				</Grid>
			</Container>
		</Box>
	);
}
