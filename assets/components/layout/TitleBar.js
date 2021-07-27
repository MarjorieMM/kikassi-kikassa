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
							<Box textAlign="center" fontWeight="fontWeightBold">
								KIKASSI KIKASSA
							</Box>
							<Box textAlign="center">La Bibliothèque d'Objets Arlésienne</Box>
						</Typography>
					</Toolbar>
				</AppBar>
			)}
		</>
	);
}
