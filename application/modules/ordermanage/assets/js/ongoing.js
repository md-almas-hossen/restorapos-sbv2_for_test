// JavaScript Document
$(document).ready(function(){
	"use strict";
                $('.search-table-field').select2({
                    placeholder: lang.type_slorder,
                     minimumInputLength: 1,
                  ajax: {
                    url: 'ongoingtable_name',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                      return {
                          q: params.term, // The term entered in the search box
                          csrf_test_name: basicinfo.csrftokeng // CSRF token
                      };
                    },
                    processResults: function (data) {
                      return {
                        results:  $.map(data, function (item) {
                              return {
                                  text: item.text,
                                  id: item.id
                              }
                          })
                      };
                    },
                    cache: true
                  }
                });
              });
			  $(document).ready(function(){
                $('.search-tablesr-field').select2({
                    placeholder: lang.type_table,
                     minimumInputLength: 1,
                  ajax: {
                    url: 'ongoingtablesearch',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                      return {
                          q: params.term, // The term entered in the search box
                          csrf_test_name: basicinfo.csrftokeng // CSRF token
                      };
                    },
                    processResults: function (data) {
                      return {
                        results:  $.map(data, function (item) {
                              return {
                                  text: item.text,
                                  id: item.id
                              }
                          })
                      };
                    },
                    cache: true
                  }
                });
              });