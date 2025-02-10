/**
 * Blocks Helper Module
 *
 * This module is responsible for importing and registering the IconSelectControl component
 * to the global lhbasics.components namespace.
 */

// Import the IconSelectControl component
import IconSelectControl from './components/icon-select-control';
import Icon from './components/icon';

// Register the IconSelectControl component to the global lhbasics.components namespace
window.lhbasics = window.lhbasics || {};
window.lhbasics.components = window.lhbasics.components || {};
window.lhbasics.components.IconSelectControl = IconSelectControl;
window.lhbasics.components.Icon = Icon;