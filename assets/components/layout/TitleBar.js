import React from "react";
import { AppBar, Typography } from "@material-ui/core";
import { makeStyles, styled } from "@material-ui/core/styles";
import { spacing } from "@material-ui/system";
import Box from "@material-ui/core/Box";
import MuiToolbar from "@material-ui/core/Toolbar";

const useStyles = makeStyles((theme) => ({
	lightAppBar: {
		backgroundColor: theme.palette.primary.main,
	},
	bold: {
		fontWeight: "bold",
	},
}));

const Toolbar = styled(MuiToolbar)(spacing);

export default function TitleBar(props) {
	const visible = props.visible;
	const classes = useStyles();
	return (
		<>
			{visible && (
				<AppBar
					position="sticky"
					color="primary"
					className={classes.lightAppBar}
				>
					<Toolbar m="auto">
						<Typography component="div">
							<Box mt={1} textAlign="center">
								<h3 className={classes.bold}>KIKASSI KIKASSA</h3>
							</Box>
							<Box mb={1} textAlign="center">
								<h4>La Bibliothèque d'Objets Arlésienne</h4>
							</Box>
						</Typography>
					</Toolbar>
				</AppBar>
			)}
		</>
	);
}
