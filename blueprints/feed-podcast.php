<?php if(!defined('KIRBY')) exit ?>
title: Podcast Feed
pages: false
files: false
fields:
  title:
    label: Podcast Title
    type:  text
  description:
    label: Description
    type:  textarea
    width: 1/2
  link:
    label: Link
    type: text
  itunesAuthor:
    label: itunesAuthor Name
    type: text
  itunesEmail:
    label: itunesEmail
    type: email
  itunesImage:
    label: itunesImage URL
    type: text
  itunesSubtitle:
    label: itunesSubtitle
    type: text
  itunesKeywords:
    label: itunesKeywords
    type: tags
  itunesBlock:
    label: Block Podcast
    type: toggle
    text: yes/no
  itunesExplicit:
    label: Explicit
    type: toggle
    text: yes/no
  itunesCategories:
    label: itunesCategories
    type: text
