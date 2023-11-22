import * as React from 'react';
import Button from '@mui/material/Button';
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import DialogContentText from '@mui/material/DialogContentText';
import DialogTitle from '@mui/material/DialogTitle';
import TagForm from "./Tags";

export default function PopUpTags() {
    const [open, setOpen] = React.useState(false);
    const [databaseTags, setDatabaseTags] = React.useState([]);

    const handleClickOpen = () => {
        setOpen(true);
    };

    const handleClose = () => {
        setOpen(false);
    };

    return (
        <div>
            <Button variant="outlined" onClick={handleClickOpen}>
                Ajouter des tags
            </Button>
            <Dialog
                open={open}
                onClose={handleClose}
                aria-labelledby="pop-up-tags-title"
                aria-describedby="pop-up-tags-description"
            >
                <DialogTitle id="pop-up-tags-title">
                    {"Ajouter des tags"}
                </DialogTitle>
                <DialogContent>
                    <div id="pop-up-tags-description">
                        <TagForm/>
                    </div>
                </DialogContent>
                <DialogActions>
                    <Button onClick={handleClose}>Fermer</Button>
                </DialogActions>
            </Dialog>
        </div>
    );
}