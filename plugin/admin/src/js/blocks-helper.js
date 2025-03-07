/**
 * Blocks Helper Module
 *
 * This module is responsible for importing and registering the IconSelectControl component
 * to the global lhbasics.components namespace.
 */

// Import components
import IconSelectControl from './components/icon-select-control';
import LHIcon from './components/icon';
import MediaSelectControl from './components/media-select-control';

window.lhbasics = window.lhbasics || {};
window.lhbasics.components = window.lhbasics.components || {};

// Register the IconSelectControl component to the global lhbasics.components namespace
window.lhbasics.components.IconSelectControl = IconSelectControl;

// Register the LHIcon component to the global lhbasics.components namespace
window.lhbasics.components.LHIcon = LHIcon;

// Register the MediaSelectControl component to the global lhbasics.components namespace
window.lhbasics.components.MediaSelectControl = MediaSelectControl;
