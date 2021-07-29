import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import Card from "@material-ui/core/Card";
import CardActionArea from "@material-ui/core/CardActionArea";
import CardActions from "@material-ui/core/CardActions";
import CardContent from "@material-ui/core/CardContent";
import CardMedia from "@material-ui/core/CardMedia";
import Button from "@material-ui/core/Button";
import Typography from "@material-ui/core/Typography";
import useCapitalize from "../utils/useCapitalize";

const useStyles = makeStyles((theme) => ({
	list: {
		// maxWidth: 345,
		// maxHeight: 450,
		maxWidth: "90%",
		maxHeight: "500px",
		marginBottom: "20px",
		margin: "auto",
	},

	featured: {
		padding: "2px",
		width: "100%",
		flexShrink: 0,
		flexGrow: 1,

		// backgroundColor: `${theme.palette.secondary.main}20`,
	},

	featuredCarousel: {
		width: "calc(100% / 4)",
	},

	catalogueVert: {
		border: `6px solid ${theme.palette.secondary.main}`,
	},
	catalogueBleu: {
		border: `6px solid ${theme.palette.primary.main}`,
	},
	image: {
		borderRadius: "10px",
		objectFit: "contain",
	},

	padding: {
		paddingBottom: 0,
	},
}));

export default function ObjectCard({
	denomination,
	description,
	photo,
	catalogue,
	list,
	mobile,
}) {
	const classes = useStyles();
	const capName = useCapitalize(denomination);
	const classList = list ? classes.list : classes.featured;
	const colorCatalog =
		catalogue === "Bleue" ? classes.catalogueBleu : classes.catalogueVert;
	const carousel = mobile || list ? "" : classes.featuredCarousel;
	return (
		<Card className={`${classList} ${carousel}`}>
			<CardActionArea>
				<CardMedia
					className={`${colorCatalog} ${classes.image}`}
					component="img"
					alt="Objet"
					height={list ? "250" : "150"}
					image={photo}
				/>
				<CardContent className={classes.padding}>
					{list ? (
						<Typography gutterBottom variant="h5" component="h2">
							{capName}
						</Typography>
					) : (
						<Typography component="p">{capName}</Typography>
					)}

					{description && (
						<Typography variant="body2" color="textSecondary" component="p">
							{description}
						</Typography>
					)}
				</CardContent>
			</CardActionArea>
			{!mobile && (
				<CardActions>
					<Button variant="contained" size="small" color="primary">
						Voir l'objet
					</Button>
				</CardActions>
			)}
		</Card>
	);
}
