<?php
    class ElementAdManagerSuccess extends Element {
        public function Render() {
            ?><div class="buyad">
            <h2 class="ad">Διαφήμιση στο Zino</h2>
            <div class="create status">
                <div class="left">
                    <img src="http://static.zino.gr/phoenix/alert-note.png" alt="Όλα καλά!" title="Όλα καλά!" />
                </div>
                <div class="right">
                    <h3>Σας ευχαριστούμε!</h3>
                    <p>Η συναλλαγή σας έχει ολοκληρωθεί.<br />
                    <strong>Σας ευχαριστούμε για την προτίμησή σας!</strong></p>
                    <p>Ελπίζουμε η συνεργασία μαζί μας να είναι άριστη.<br />Σε περίπτωση οποιουδήποτε 
                    προβλήματος ή ερώτησης, μην διστάσετε να επικοινωνήσετε μαζί μας στο 
                    <a href="mailto:ads@zino.gr">ads@zino.gr</a></p>
                    <h3>Επόμενα βήματα</h3>
                    <p>Η διαφήμισή σας στο Zino θα ενεργοποιηθεί αυτόματα μόλις επιβεβαιωθεί η πληρωμή 
                    σας.<br />Μπορείτε να παρακολουθείτε την πορεία της διαφήμισής σας από την <a href="?p=admanager/list">σελίδα 
                    διαχείρησης διαφημίσεων</a>.</p>
                </div>
            </div>
            </div><?php
        }
    }
?>
