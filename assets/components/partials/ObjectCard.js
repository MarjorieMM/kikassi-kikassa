import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import Card from "@material-ui/core/Card";
import CardActionArea from "@material-ui/core/CardActionArea";
import CardActions from "@material-ui/core/CardActions";
import CardContent from "@material-ui/core/CardContent";
import CardMedia from "@material-ui/core/CardMedia";
import Button from "@material-ui/core/Button";
import Typography from "@material-ui/core/Typography";

const greenAnt = "#439b56";
const blueAnt = "#5594c0";

const useStyles = makeStyles({
	root: {
		maxWidth: 345,
	},
	catalogueVert: {
		border: `4px solid ${greenAnt}`,
	},
	catalogueBleu: {
		border: `4px solid ${blueAnt}`,
	},
});

export default function ObjectCard({ object }) {
	const classes = useStyles();
	const catalogue = object.catalogue.categorie_fourmi;
	const photo = object.photos.map((photo) => photo.lien);

	return (
		<Card className={classes.root}>
			<CardActionArea>
				<CardMedia
					className={
						catalogue === "Bleue"
							? classes.catalogueBleu
							: classes.catalogueVert
					}
					component="img"
					alt="Objet"
					height="140"
					image={
						photo.length > 0
							? `photos/${photo[0]}`
							: "photos/logos/kikassa-default.png"
					}
					// title={object.denomination}
				/>
				<CardContent>
					<Typography gutterBottom variant="h5" component="h2">
						{object.denomination}
					</Typography>
					<Typography variant="body2" color="textSecondary" component="p">
						{object.description}
					</Typography>
				</CardContent>
			</CardActionArea>
			<CardActions>
				<Button variant="contained" size="small" color="primary">
					Voir plus
				</Button>
				{/* <Button size="small" color="primary">
					Learn More
				</Button> */}
			</CardActions>
		</Card>
	);
}
