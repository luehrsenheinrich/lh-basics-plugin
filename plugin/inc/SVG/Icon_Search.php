<?php
/**
 * Ranked search across icon names and provider keywords.
 *
 * @package lhbasicsp
 */

namespace WpMunich\basics\plugin\SVG;

/**
 * Search without reading SVG files or exposing filesystem paths.
 */
class Icon_Search {
	/**
	 * Find icons matching every query term, with name matches first.
	 *
	 * @param Icon[] $icons Registered icons.
	 * @param string $query User search query.
	 * @return Icon[] Ranked icons, preserving slug keys.
	 */
	public function search( array $icons, string $query ): array {
		$query = $this->normalize( $query );
		if ( '' === $query ) {
			return $icons;
		}

		$terms   = array_unique( explode( ' ', $query ) );
		$matches = array();
		$scores  = array();
		foreach ( $icons as $key => $icon ) {
			$names    = array( $this->normalize( $icon->get_title() ), $this->normalize( $icon->get_slug() ) );
			$keywords = array_map( array( $this, 'normalize' ), $icon->get_keywords() );
			$score    = 0;
			foreach ( $terms as $term ) {
				$term_score = $this->score_term( $term, $names, $keywords );
				if ( null === $term_score ) {
					continue 2;
				}
				$score += $term_score;
			}
			$matches[ $key ] = $icon;
			$scores[ $key ]  = in_array( $query, $names, true ) ? -1 : $score;
		}

		// PHP's stable sort retains provider order for equally relevant matches.
		asort( $scores, SORT_NUMERIC );
		$ranked = array();
		foreach ( array_keys( $scores ) as $key ) {
			$ranked[ $key ] = $matches[ $key ];
		}
		return $ranked;
	}

	/**
	 * Normalize case and separators used by Carbon slugs and keyword phrases.
	 *
	 * @param string $text Search text.
	 * @return string Normalized text.
	 */
	public function normalize( string $text ): string {
		return trim( preg_replace( '/[\s_-]+/u', ' ', mb_strtolower( $text, 'UTF-8' ) ) );
	}

	/**
	 * Prefer names, then keyword substrings, then typo-tolerant name matches.
	 *
	 * @param string $term Search term.
	 * @param array  $names Normalized title and slug.
	 * @param array  $keywords Normalized keywords.
	 * @return int|null Relevance score, or null when no field matches.
	 */
	private function score_term( string $term, array $names, array $keywords ): ?int {
		foreach ( $names as $name ) {
			if ( str_contains( $name, $term ) ) {
				return 0;
			}
		}
		foreach ( $keywords as $keyword ) {
			if ( str_contains( $keyword, $term ) ) {
				return 1;
			}
		}

		$length = mb_strlen( $term, 'UTF-8' );
		if ( $length < 4 ) {
			return null;
		}
		foreach ( $names as $name ) {
			foreach ( explode( ' ', $name ) as $word ) {
				// Map Unicode characters to bytes consistently for levenshtein().
				$characters = array_unique( mb_str_split( $term . $word, 1, 'UTF-8' ) );
				if ( count( $characters ) > 255 ) {
					continue;
				}
				$map = array_combine( $characters, array_map( 'chr', range( 1, count( $characters ) ) ) );
				if ( levenshtein( strtr( $term, $map ), strtr( $word, $map ) ) / $length <= 0.3 ) {
					return 2;
				}
			}
		}
		return null;
	}
}
