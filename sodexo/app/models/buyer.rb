class Buyer < ActiveRecord::Base
  has_many :transactions, :as => :trans
end
