/**
 * Blocks Helper Module
 *
 * This module is responsible for importing and registering the IconSelectControl component
 * to the global lhbasics.components namespace.
 */

// Import components
import IconSelectControl from './components/icon-select-control';
import LHIcon from './components/icon';
import EntitySelectControl, {
	PostSelectControl,
	TaxonomySelectControl,
} from './components/entity-select-control';

window.lhbasics = window.lhbasics || {};
window.lhbasics.components = window.lhbasics.components || {};

// Register the IconSelectControl component to the global lhbasics.components namespace
window.lhbasics.components.IconSelectControl = IconSelectControl;
window.lhbasics.components.EntitySelectControl = EntitySelectControl;
window.lhbasics.components.PostSelectControl = PostSelectControl;
window.lhbasics.components.TaxonomySelectControl = TaxonomySelectControl;

// Register the LHIcon component to the global lhbasics.components namespace
window.lhbasics.components.LHIcon = LHIcon;
