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

const useStyles = makeStyles((theme) => ({
	margin: {
		margin: `${theme.spacing(1)}px 0`,
	},
	padding: {
		padding: theme.spacing(2),
	},
}));

function Connexion() {
	const classes = useStyles();
	return (
		<Box>
			<Paper elevation={10} className={classes.padding}>
				<Typography align="center" className={classes.margin} variant="h5">
					Connectez-vous à votre compte
				</Typography>
				<form noValidate autoComplete="off">
					<TextField
						className={classes.margin}
						required
						id="email"
						label="Votre addresse email"
						variant="outlined"
						fullWidth
					/>
					<TextField
						className={classes.margin}
						id="password"
						label="Votre Mot de Passe"
						type="password"
						variant="outlined"
						fullWidth
					/>
					<Button variant="contained" size="small" color="secondary">
						Valider
					</Button>
				</form>
			</Paper>
		</Box>
	);
}

export default Connexion;
