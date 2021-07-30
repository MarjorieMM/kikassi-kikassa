import React from "react";
import Box from "@material-ui/core/Box";
import Grid from "@material-ui/core/Grid";
import Paper from "@material-ui/core/Paper";
import Button from "@material-ui/core/Button";
import TextField from "@material-ui/core/TextField";
import Container from "@material-ui/core/Container";
import ListSubheader from "@material-ui/core/ListSubheader";
import { makeStyles } from "@material-ui/core/styles";
import Typography from "@material-ui/core/Typography";
import { Link } from "react-router-dom";

const useStyles = makeStyles((theme) => ({
	margin: {
		margin: `${theme.spacing(1)}px 0`,
		fontWeight: "bold",
	},
	marginTop: {
		marginTop: theme.spacing(2),
		fontWeight: "bold",
	},
	paper: {
		padding: `${theme.spacing(4)}px ${theme.spacing(3)}px`,
		borderRadius: "30px",
	},
}));

function Connexion() {
	const classes = useStyles();
	return (
		<Box fontWeight="bold">
			<Paper elevation={5} className={classes.paper}>
				<Typography
					align="center"
					className={classes.margin}
					variant="h5"
					color="secondary"
				>
					Connectez-vous à votre compte
					<br /> Kikassi Kikassa
				</Typography>
				<form noValidate noValidate autoComplete="off">
					<TextField
						className={classes.margin}
						required
						id="email"
						label="Votre addresse email"
						variant="outlined"
						fullWidth
					/>
					<TextField
						required
						className={classes.margin}
						id="password"
						label="Votre Mot de Passe"
						type="password"
						helperText={<Link to="/">Mot de passe oublié</Link>}
						variant="outlined"
						fullWidth
					/>
					<Box textAlign="center">
						<Button
							className={classes.marginTop}
							variant="contained"
							size="large"
							color="secondary"
						>
							Valider
						</Button>
					</Box>
				</form>
			</Paper>
		</Box>
	);
}

export default Connexion;
