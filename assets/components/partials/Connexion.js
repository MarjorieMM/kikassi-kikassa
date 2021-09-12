import React, { useState } from "react";
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
import useLogin from "../utils/useLogin";

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

function Connexion({ setToken }) {
	const [email, setEmail] = useState();
	const [password, setPassword] = useState();
	const {
		data: adherent,
		error,
		isLoaded,
	} = useLogin("/api/adherents", {
		email,
		password,
	});

	const handleSubmit = async (e) => {
		e.preventDefault();
		console.log(email, password);
		await fetch("/api/adhesion_bibliotheques", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({ email, password }),
		}).then((data) => console.log(data.json()));
	};
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
				<form onSubmit={handleSubmit} noValidate autoComplete="off">
					<TextField
						className={classes.margin}
						required
						id="email"
						label="Votre addresse email"
						variant="outlined"
						fullWidth
						onChange={(e) => setEmail(e.target.value)}
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
						onChange={(e) => setPassword(e.target.value)}
					/>
					<Box textAlign="center">
						<Button
							className={classes.marginTop}
							variant="contained"
							size="large"
							color="secondary"
							type="submit"
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
