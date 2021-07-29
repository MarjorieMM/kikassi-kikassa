import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import { Link } from "react-router-dom";
import insta from "../../../public/photos/logos/Instagram_Glyph_Gradient_RGB.png";
import fb from "../../../public/photos/logos/f_logo_RGB-Blue_144.png";
import { AppBar, Typography, Grid, Toolbar, Divider } from "@material-ui/core";
import Box from "@material-ui/core/Box";

const useStyles = makeStyles((theme) => ({
	appBar: {
		backgroundColor: theme.palette.primary.dark,
		borderRadius: "5px",
	},
	lightAppBar: {
		backgroundColor: theme.palette.primary.main,
	},
	margin: { marginRight: "10px" },
	linkColor: {
		color: "#fff",
	},
	marginAuto: {
		margin: "20px auto",
	},
}));

function Footer() {
	const classes = useStyles();
	return (
		<footer className={classes.appBar}>
			<AppBar position="sticky" color="primary" className={classes.appBar}>
				<Toolbar>
					<Grid container>
						<Grid
							item
							xs={12}
							sm={4}
							container
							direction="column"
							alignItems="center"
						>
							<Typography component="div">
								<Box>Nous contacter :</Box>
								<Box fontWeight="fontWeightBold">KIKASSI KIKASSA</Box>
								<Box>7 Rue de la Roquette</Box>
								<Box>13200 Arles</Box>
							</Typography>
						</Grid>
						{/* <Grid item md={1}>
							<Divider orientation="vertical" flexItem />
						</Grid> */}
						<Grid item xs={12} sm={4} className={classes.marginAuto}>
							<Typography component="div">
								<Box textAlign="center">
									<Link
										className={classes.margin}
										to={{ pathname: "https://fr-fr.facebook.com/Kika.Arles/" }}
										target="_blank"
										rel="noreferrer"
									>
										<img src={fb} width="50px" alt="logo-facebook" />
									</Link>
									<Link
										to={{
											pathname: "https://www.instagram.com/kikassi_kikassa",
										}}
										target="_blank"
										rel="noreferrer"
									>
										<img src={insta} width="50px" alt="logo-instagram" />
									</Link>
								</Box>
							</Typography>
						</Grid>
						<Grid
							item
							xs={12}
							sm={4}
							container
							direction="column"
							alignItems="center"
						>
							<Typography component="div">
								<Box>
									<Link className={classes.linkColor} to="/">
										Mentions Légales
									</Link>
								</Box>
								<Box>
									<Link className={classes.linkColor} to="/">
										A Propos
									</Link>
								</Box>
								<Box>
									<Link className={classes.linkColor} to="/">
										Réglement
									</Link>
								</Box>
								<Box>
									<Link className={classes.linkColor} to="/">
										Remerciements
									</Link>
								</Box>
							</Typography>
						</Grid>
					</Grid>
				</Toolbar>
			</AppBar>
		</footer>
	);
}

export default Footer;
