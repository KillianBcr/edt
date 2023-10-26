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

    const handleClickOpen = () => {
        setOpen(true);
    };

    const handleClose = () => {
        setOpen(false);
    };

    return (
        <div>
            <Button variant="outlined" onClick={handleClickOpen}>
                Ajouter des filtres
            </Button>
            <Dialog
                open={open}
                onClose={handleClose}
                aria-labelledby="pop-up-tags-title"
                aria-describedby="pop-up-tags-description"
            >
                <DialogTitle id="pop-up-tags-title">
                    {"Ajouter des filtres"}
                </DialogTitle>
                <DialogContent>
                    <DialogContentText id="pop-up-tags-description">
                        <TagForm/>
                    </DialogContentText>
                </DialogContent>
                <DialogActions>
                    <Button onClick={handleClose}>Fermer</Button>
                </DialogActions>
            </Dialog>
        </div>
    );
}