# SciNet

This is the custom course management site I built over the course of multiple semesters.

Note: Don't ever develop software the way I did for this! There are many pieces that 
need to be redesigned, things that are hard-coded that should be in config files,
things that depend too much on directories that happened to be set up a certain way
at the time. In general, it's a terrible idea to add features to something being
used actively, when you have insufficient time and no proper way to fully test things
before they are deployed.

In the earlier incarnations of "SciNet", the site acted as a complete replacement for
Blackboard and the like, handling assignment submissions, grading, etc. Remnants of these
exist in the repository, but the current version is now used only for attendance and the
"Live Practice" and "Contest" features, both of which are virtual clicker systems
that allow students who are logged in as present on a given day to participate.
